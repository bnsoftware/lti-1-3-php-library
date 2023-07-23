<?php

namespace BNSoftware\Lti1p3;

class LtiNamesRolesProvisioningService extends LtiAbstractService
{
    public const CONTENT_TYPE_MEMBERSHIP_CONTAINER = 'application/vnd.ims.lti-nrps.v2.membershipcontainer+json';

    /**
     * @return array
     */
    public function getScope(): array
    {
        return [LtiConstants::NRPS_SCOPE_MEMBERSHIP_READONLY];
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        $request = new ServiceRequest(
            ServiceRequest::METHOD_GET,
            $this->getServiceData()['context_memberships_url'],
            ServiceRequest::TYPE_GET_MEMBERSHIPS
        );
        $request->setAccept(static::CONTENT_TYPE_MEMBERSHIP_CONTAINER);

        return $this->getAll($request, 'members');
    }
}
