<?php

namespace BNSoftware\Lti1p3;

class LtiAssignmentsGradesService extends LtiAbstractService
{
    public const CONTENT_TYPE_SCORE = 'application/vnd.ims.lis.v1.score+json';
    public const CONTENT_TYPE_LINE_ITEM = 'application/vnd.ims.lis.v2.lineitem+json';
    public const CONTENT_TYPE_LINE_ITEM_CONTAINER = 'application/vnd.ims.lis.v2.lineitemcontainer+json';
    public const CONTENT_TYPE_RESULT_CONTAINER = 'application/vnd.ims.lis.v2.resultcontainer+json';

    /**
     * @return array
     */
    public function getScope(): array
    {
        return $this->getServiceData()['scope'];
    }

    // https://www.imsglobal.org/spec/lti-ags/v2p0#assignment-and-grade-service-claim
    // When an LTI message is launching a resource associated to one and only one line item,
    // the claim must include the endpoint URL for accessing the associated line item;
    // in all other cases, this property must be either blank or not included in the claim.
    public function getResourceLaunchLineItem(): ?LtiLineItem
    {
        $serviceData = $this->getServiceData();
        if (empty($serviceData['lineitem'])) {
            return null;
        }

        return LtiLineItem::new()->setId($serviceData['lineitem']);
    }

    /**
     * @throws LtiException
     */
    public function putGrade(LtiGrade $grade, LtiLineItem $lineItem = null): array
    {
        $this->validateScopes([LtiConstants::AGS_SCOPE_SCORE]);

        $lineItem = $this->ensureLineItemExists($lineItem);

        $scoreUrl = $lineItem->getId();

        // Place '/scores' before url params
        $pos = strpos($scoreUrl, '?');
        $scoreUrl = $pos === false
            ? $scoreUrl . '/scores'
            : substr_replace($scoreUrl, '/scores', $pos, 0);

        $request = new ServiceRequest(
            ServiceRequest::METHOD_POST,
            $scoreUrl,
            ServiceRequest::TYPE_SYNC_GRADE
        );
        $request->setBody($grade);
        $request->setContentType(static::CONTENT_TYPE_SCORE);

        return $this->makeServiceRequest($request);
    }

    /**
     * @throws LtiException
     */
    public function findLineItem(LtiLineItem $newLineItem): ?LtiLineItem
    {
        $lineItems = $this->getLineItems();

        foreach ($lineItems as $lineItem) {
            if ($this->isMatchingLineItem($lineItem, $newLineItem)) {
                return new LtiLineItem ($lineItem);
            }
        }

        return null;
    }

    /**
     * @param LtiLineItem $lineItemToUpdate
     * @return LtiLineItem
     */
    public function updateLineItem(LtiLineItem $lineItemToUpdate): LtiLineItem
    {
        $request = new ServiceRequest(
            ServiceRequest::METHOD_PUT,
            $this->getServiceData()['lineitem'],
            ServiceRequest::TYPE_UPDATE_LINE_ITEM
        );

        $request->setBody($lineItemToUpdate)
            ->setContentType(static::CONTENT_TYPE_LINE_ITEM)
            ->setAccept(static::CONTENT_TYPE_LINE_ITEM);

        $updatedLineItem = $this->makeServiceRequest($request);

        return new LtiLineItem($updatedLineItem['body']);
    }

    /**
     * @param LtiLineItem $newLineItem
     * @return LtiLineItem
     */
    public function createLineItem(LtiLineItem $newLineItem): LtiLineItem
    {
        $request = new ServiceRequest(
            ServiceRequest::METHOD_POST,
            $this->getServiceData()['lineitems'],
            ServiceRequest::TYPE_CREATE_LINE_ITEM
        );
        $request->setBody($newLineItem)
            ->setContentType(static::CONTENT_TYPE_LINE_ITEM)
            ->setAccept(static::CONTENT_TYPE_LINE_ITEM);
        $createdLineItem = $this->makeServiceRequest($request);

        return new LtiLineItem ($createdLineItem['body']);
    }

    /**
     * @return array
     */
    public function deleteLineItem(): array
    {
        $request = new ServiceRequest(
            ServiceRequest::METHOD_DELETE,
            $this->getServiceData()['lineitem'],
            ServiceRequest::TYPE_DELETE_LINE_ITEM
        );

        return $this->makeServiceRequest($request);
    }

    /**
     * @param LtiLineItem $newLineItem
     * @return LtiLineItem
     * @throws LtiException
     */
    public function findOrCreateLineItem(LtiLineItem $newLineItem): LtiLineItem
    {
        return $this->findLineItem($newLineItem) ?? $this->createLineItem($newLineItem);
    }

    /**
     * @param ?LtiLineItem $lineItem
     * @return mixed
     */
    public function getGrades(?LtiLineItem $lineItem = null)
    {
        $lineItem = $this->ensureLineItemExists($lineItem);
        $resultsUrl = $lineItem->getId();

        // Place '/results' before url params
        $pos = strpos($resultsUrl, '?');
        $resultsUrl = $pos === false
            ? $resultsUrl . '/results'
            : substr_replace($resultsUrl, '/results', $pos, 0);

        $request = new ServiceRequest(
            ServiceRequest::METHOD_GET,
            $resultsUrl,
            ServiceRequest::TYPE_GET_GRADES
        );
        $request->setAccept(static::CONTENT_TYPE_RESULT_CONTAINER);
        $scores = $this->makeServiceRequest($request);

        return $scores['body'];
    }


    /**
     * @return array
     * @throws LtiException
     */
    public function getLineItems(): array
    {
        $this->validateScopes([LtiConstants::AGS_SCOPE_LINE_ITEM, LtiConstants::AGS_SCOPE_LINE_ITEM_READONLY]);

        $request = new ServiceRequest(
            ServiceRequest::METHOD_GET,
            $this->getServiceData()['lineitems'],
            ServiceRequest::TYPE_GET_LINE_ITEMS
        );
        $request->setAccept(static::CONTENT_TYPE_LINE_ITEM_CONTAINER);

        $lineItems = $this->getAll($request);

        // If there is only one item, then wrap it in an array so the foreach works
        if (isset($lineItems['body']['id'])) {
            $lineItems['body'] = [$lineItems['body']];
        }

        return $lineItems;
    }


    /**
     * @param string $url
     * @return LtiLineItem
     * @throws LtiException
     */
    public function getLineItem(string $url): LtiLineItem
    {
        $this->validateScopes([LtiConstants::AGS_SCOPE_LINE_ITEM, LtiConstants::AGS_SCOPE_LINE_ITEM_READONLY]);

        $request = new ServiceRequest(
            ServiceRequest::METHOD_GET,
            $url,
            ServiceRequest::TYPE_GET_LINE_ITEM
        );
        $request->setAccept(static::CONTENT_TYPE_LINE_ITEM);

        $response = $this->makeServiceRequest($request)['body'];

        return new LtiLineItem ($response);
    }

    /**
     * @param ?LtiLineItem $lineItem
     * @return LtiLineItem
     * @throws LtiException
     */
    private function ensureLineItemExists(LtiLineItem $lineItem = null): LtiLineItem
    {
        // If no line item is passed in, attempt to use the one associated with
        // this launch.
        if (!isset($lineItem)) {
            $lineItem = $this->getResourceLaunchLineItem();
        }

        // If none exists still, create a default line item.
        if (!isset($lineItem)) {
            $defaultLineItem = LtiLineItem::new()
                ->setLabel('default')
                ->setScoreMaximum(100);
            $lineItem = $this->createLineItem($defaultLineItem);
        }

        // If the line item does not contain an ID, find or create it.
        if (empty($lineItem->getId())) {
            $lineItem = $this->findOrCreateLineItem($lineItem);
        }

        return $lineItem;
    }

    /**
     * @param array       $lineItem
     * @param LtiLineItem $newLineItem
     * @return bool
     */
    private function isMatchingLineItem(array $lineItem, LtiLineItem $newLineItem): bool
    {
        return $newLineItem->getTag() == ($lineItem['tag'] ?? null) &&
            $newLineItem->getResourceId() == ($lineItem['resourceId'] ?? null) &&
            $newLineItem->getResourceLinkId() == ($lineItem['resourceLinkId'] ?? null);
    }
}
