<?php

namespace BNSoftware\Lti1p3;

class LtiConstants
{
    public const V1_3 = '1.3.0';

    // Required message claims
    public const MESSAGE_TYPE = 'https://purl.imsglobal.org/spec/lti/claim/message_type';
    public const VERSION = 'https://purl.imsglobal.org/spec/lti/claim/version';
    public const DEPLOYMENT_ID = 'https://purl.imsglobal.org/spec/lti/claim/deployment_id';
    public const TARGET_LINK_URI = 'https://purl.imsglobal.org/spec/lti/claim/target_link_uri';
    public const RESOURCE_LINK = 'https://purl.imsglobal.org/spec/lti/claim/resource_link';
    public const ROLES = 'https://purl.imsglobal.org/spec/lti/claim/roles';
    public const FOR_USER = 'https://purl.imsglobal.org/spec/lti/claim/for_user';

    // Optional message claims
    public const CONTEXT = 'https://purl.imsglobal.org/spec/lti/claim/context';
    public const TOOL_PLATFORM = 'https://purl.imsglobal.org/spec/lti/claim/tool_platform';
    public const ROLE_SCOPE_MENTOR = 'https://purlimsglobal.org/spec/lti/claim/role_scope_mentor';
    public const LAUNCH_PRESENTATION = 'https://purl.imsglobal.org/spec/lti/claim/launch_presentation';
    public const LIS = 'https://purl.imsglobal.org/spec/lti/claim/lis';
    public const CUSTOM = 'https://purl.imsglobal.org/spec/lti/claim/custom';

    // LTI DL
    public const DL_CONTENT_ITEMS = 'https://purl.imsglobal.org/spec/lti-dl/claim/content_items';
    public const DL_DATA = 'https://purl.imsglobal.org/spec/lti-dl/claim/data';
    public const DL_DEEP_LINK_SETTINGS = 'https://purl.imsglobal.org/spec/lti-dl/claim/deep_linking_settings';
    public const DL_RESOURCE_LINK_TYPE = 'ltiResourceLink';
    public const DL_RESOURCE_TARGET_IFRAME = 'iframe';

    // LTI NRPS
    public const NRPS_CLAIM_SERVICE = 'https://purl.imsglobal.org/spec/lti-nrps/claim/namesroleservice';
    public const NRPS_SCOPE_MEMBERSHIP_READONLY = 'https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly';

    // LTI AGS
    public const AGS_CLAIM_ENDPOINT = 'https://purl.imsglobal.org/spec/lti-ags/claim/endpoint';
    public const AGS_SCOPE_LINE_ITEM = 'https://purl.imsglobal.org/spec/lti-ags/scope/lineitem';
    public const AGS_SCOPE_LINE_ITEM_READONLY = 'https://purl.imsglobal.org/spec/lti-ags/scope/lineitem.readonly';
    public const AGS_SCOPE_RESULT_READONLY = 'https://purl.imsglobal.org/spec/lti-ags/scope/result.readonly';
    public const AGS_SCOPE_SCORE = 'https://purl.imsglobal.org/spec/lti-ags/scope/score';

    // LTI GS
    public const GS_CLAIM_SERVICE = 'https://purl.imsglobal.org/spec/lti-gs/claim/groupsservice';

    // User Vocab
    public const SYSTEM_ADMINISTRATOR = 'http://purl.imsglobal.org/vocab/lis/v2/system/person#Administrator';
    public const SYSTEM_NONE = 'http://purl.imsglobal.org/vocab/lis/v2/system/person#None';
    public const SYSTEM_ACCOUNT_ADMIN = 'http://purl.imsglobal.org/vocab/lis/v2/system/person#AccountAdmin';
    public const SYSTEM_CREATOR = 'http://purl.imsglobal.org/vocab/lis/v2/system/person#Creator';
    public const SYSTEM_SYS_ADMIN = 'http://purl.imsglobal.org/vocab/lis/v2/system/person#SysAdmin';
    public const SYSTEM_SYS_SUPPORT = 'http://purl.imsglobal.org/vocab/lis/v2/system/person#SysSupport';
    public const SYSTEM_USER = 'http://purl.imsglobal.org/vocab/lis/v2/system/person#User';
    public const INSTITUTION_ADMINISTRATOR = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Administrator';
    public const INSTITUTION_FACULTY = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Faculty';
    public const INSTITUTION_GUEST = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Guest';
    public const INSTITUTION_NONE = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#None';
    public const INSTITUTION_OTHER = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Other';
    public const INSTITUTION_STAFF = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Staff';
    public const INSTITUTION_STUDENT = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Student';
    public const INSTITUTION_ALUMNI = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Alumni';
    public const INSTITUTION_INSTRUCTOR = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Instructor';
    public const INSTITUTION_LEARNER = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Learner';
    public const INSTITUTION_MEMBER = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Member';
    public const INSTITUTION_MENTOR = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Mentor';
    public const INSTITUTION_OBSERVER = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#Observer';
    public const INSTITUTION_PROSPECTIVE_STUDENT = 'http://purl.imsglobal.org/vocab/lis/v2/institution/person#ProspectiveStudent';
    public const MEMBERSHIP_ADMINISTRATOR = 'http://purl.imsglobal.org/vocab/lis/v2/membership#Administrator';
    public const MEMBERSHIP_CONTENT_DEVELOPER = 'http://purl.imsglobal.org/vocab/lis/v2/membership#ContentDeveloper';
    public const MEMBERSHIP_INSTRUCTOR = 'http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor';
    public const MEMBERSHIP_LEARNER = 'http://purl.imsglobal.org/vocab/lis/v2/membership#Learner';
    public const MEMBERSHIP_MENTOR = 'http://purl.imsglobal.org/vocab/lis/v2/membership#Mentor';
    public const MEMBERSHIP_MANAGER = 'http://purl.imsglobal.org/vocab/lis/v2/membership#Manager';
    public const MEMBERSHIP_MEMBER = 'http://purl.imsglobal.org/vocab/lis/v2/membership#Member';
    public const MEMBERSHIP_OFFICER = 'http://purl.imsglobal.org/vocab/lis/v2/membership#Officer';

    // Context sub-roles
    public const MEMBERSHIP_EXTERNAL_INSTRUCTOR = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#ExternalInstructor';
    public const MEMBERSHIP_GRADER = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#Grader';
    public const MEMBERSHIP_GUEST_INSTRUCTOR = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#GuestInstructor';
    public const MEMBERSHIP_LECTURER = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#Lecturer';
    public const MEMBERSHIP_PRIMARY_INSTRUCTOR = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#PrimaryInstructor';
    public const MEMBERSHIP_SECONDARY_INSTRUCTOR = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#SecondaryInstructor';
    public const MEMBERSHIP_TA = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistant';
    public const MEMBERSHIP_TA_GROUP = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistantGroup';
    public const MEMBERSHIP_TA_OFFERING = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistantOffering';
    public const MEMBERSHIP_TA_SECTION = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistantSection';
    public const MEMBERSHIP_TA_SECTION_ASSOCIATION = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistantSectionAssociation';
    public const MEMBERSHIP_TA_TEMPLATE = 'http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistantTemplate';

    // Context Vocab
    public const COURSE_TEMPLATE = 'http://purl.imsglobal.org/vocab/lis/v2/course#CourseTemplate';
    public const COURSE_OFFERING = 'http://purl.imsglobal.org/vocab/lis/v2/course#CourseOffering';
    public const COURSE_SECTION = 'http://purl.imsglobal.org/vocab/lis/v2/course#CourseSection';
    public const COURSE_GROUP = 'http://purl.imsglobal.org/vocab/lis/v2/course#Group';

    // Message Types
    public const MESSAGE_TYPE_DEEP_LINK = 'LtiDeepLinkingRequest';
    public const MESSAGE_TYPE_DEEP_LINK_RESPONSE = 'LtiDeepLinkingResponse';
    public const MESSAGE_TYPE_RESOURCE = 'LtiResourceLinkRequest';
    public const MESSAGE_TYPE_SUBMISSION_REVIEW = 'LtiSubmissionReviewRequest';
}
