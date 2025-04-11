<?php

/**
 * Generated 2019-11-17T16:07:15+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\Actions\InvokePostMethodQuery;
use Office365\Runtime\ClientResult;
use Office365\Runtime\ClientValueCollection;
use Office365\Runtime\Paths\EntityPath;
use Office365\Runtime\ResourcePath;
use Office365\Runtime\ServerTypeInfo;
use Office365\SharePoint\Taxonomy\TaxonomyFieldValue;
use Office365\SharePoint\Taxonomy\TaxonomyFieldValueCollection;

/**
 * Specifies 
 * a list 
 * item.Contains CSOM expando fields, 
 * which correspond to the fields (2) defined 
 * in the parent list. The 
 * CSOM expando field name is the name of the field (2) defined in the list 
 * and the CSOM expando field value is the field (2) value in the list item. The 
 * following table specifies the mapping between field types and 
 * values.Field typeMapping to a valueIntegerWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Int32.TextWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.NoteWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.DateTimeWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM DateTime.CounterIt MUST be a CSOM Int32.ChoiceWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.LookupWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Object of 
 *   type FieldLookupValue, as specified in section 3.2.5.50.BooleanWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Boolean.NumberWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Double.CurrencyWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Double.URLWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Object of type FieldUrlValue, asspecified in 
 *   section 3.2.5.60.ComputedN/AThreadingWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.GuidWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM GUID.MultiChoiceWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Array.GridChoiceWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Array.CalculatedN/AFileWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.AttachmentsWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Boolean.UserWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Object of type FieldUserValue, asspecified 
 *   in section 3.2.5.63.RecurrenceWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Boolean.CrossProjectLinkWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Boolean.ModStatWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Int32.ErrorN/AContentTypeIdWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be CSOM Object of type ContentTypeId, as specified in section 3.2.5.30.PageSeparatorN/AThreadIndexWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.WorkflowStatusWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Int32.AllDayEventWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Boolean.WorkflowEventTypeWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Int32.FacilitiesWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Array.FreeBusyWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.OverbookWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.ConfidentialWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Boolean.WhatsNewWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Boolean.DueDateWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM DateTime.AllowEditingWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Boolean.SendToWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Array.ConfirmationsWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.CallToWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM Array.CallTimeWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM DateTime.WhereaboutWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.FromWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM DateTime.UntilWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM DateTime.ContactInfoWhen its value is undefined it MUST be NULL, otherwise 
 *   it MUST be a CSOM String.The Client_Title, CommentsDisabled, CommentsDisabledScope, DisplayName, 
 * EffectiveBasePermissions, EffectiveBasePermissionsForUI, 
 * HasUniqueRoleAssignments and IconOverlay properties are not included in the default 
 * scalar property set for this type.
 */
class ListItem extends SecurableObject
{

    /**
     * Ensure ListItem entity type name (mandatory property)
     * @param SPList $list
     */
    public function ensureTypeName(SPList $list)
    {
        if (!isset($this->typeName)) {
            $list->ensureProperty("ListItemEntityTypeFullName", function () use ($list){
                $this->typeName = $list->getListItemEntityTypeFullName();
            });
        }
    }
    /**
     * @return AttachmentCollection
     */
    public function getAttachmentFiles()
    {
        return $this->getProperty("AttachmentFiles",
            new AttachmentCollection($this->getContext(),
                new ResourcePath("AttachmentFiles", $this->getResourcePath())));
    }
    /**
     * Gets the parent list that contains the list item.
     * @return SPList
     */
    public function getParentList()
    {
        return $this->getProperty("ParentList",
            new SPList($this->getContext(),new ResourcePath("parentlist", $this->getResourcePath())));
    }
    /**
     * Gets the associated Folder resource.
     * @return Folder
     */
    public function getFolder()
    {
        return $this->getProperty("Folder",
            new Folder($this->getContext(),new ResourcePath("Folder", $this->getResourcePath())));
    }
    /**
     * Gets the associated File resource.
     * @return File
     */
    public function getFile()
    {
        return $this->getProperty("File",
            new File($this->getContext(),new ResourcePath("File", $this->getResourcePath())));
    }
    /**
     * Indicates 
     * whether comments for this item are disabled or not.
     * @return bool
     */
    public function getCommentsDisabled()
    {
        return $this->getProperty("CommentsDisabled");
    }

    /**
     * Indicates
     * whether comments for this item are disabled or not.
     *
     * @return self
     * @var bool
     */
    public function setCommentsDisabled($value)
    {
        return $this->setProperty("CommentsDisabled", $value, true);
    }
    /**
     * Indicates 
     * at what scope comments are disabled.
     * @return integer
     */
    public function getCommentsDisabledScope()
    {
        return $this->getProperty("CommentsDisabledScope");
    }
    /**
     * Indicates 
     * at what scope comments are disabled.
     * @var integer
     */
    public function setCommentsDisabledScope($value)
    {
        $this->setProperty("CommentsDisabledScope", $value, true);
    }
    /**
     * @return ListItemComplianceInfo
     */
    public function getComplianceInfo()
    {
        return $this->getProperty("ComplianceInfo", new ListItemComplianceInfo());
    }
    /**
     * @var ListItemComplianceInfo
     */
    public function setComplianceInfo($value)
    {
        $this->setProperty("ComplianceInfo", $value, true);
    }
    /**
     * Specifies 
     * the display 
     * name of the list item. It MUST 
     * NOT be NULL. It MUST NOT be empty. 
     * @return string
     */
    public function getDisplayName()
    {
        return $this->getProperty("DisplayName");
    }
    /**
     * Specifies 
     * the display 
     * name of the list item. It MUST 
     * NOT be NULL. It MUST NOT be empty. 
     * @var string
     */
    public function setDisplayName($value)
    {
        $this->setProperty("DisplayName", $value, true);
    }
    /**
     * Specifies 
     * the permissions 
     * on the list item that are 
     * assigned to the current user.It MUST 
     * NOT be NULL. 
     * @return BasePermissions
     */
    public function getEffectiveBasePermissions()
    {
        return $this->getProperty("EffectiveBasePermissions", new BasePermissions());
    }
    /**
     * Specifies 
     * the permissions 
     * on the list item that are 
     * assigned to the current user.It MUST 
     * NOT be NULL. 
     * @var BasePermissions
     */
    public function setEffectiveBasePermissions($value)
    {
        $this->setProperty("EffectiveBasePermissions", $value, true);
    }
    /**
     * Specifies 
     * the effective base permissions for the current user, as they SHOULD be 
     * displayed in the user interface (UI).<61> If the 
     * list is not in read-only UI mode, the value of EffectiveBasePermissionsForUI 
     * MUST be the same as the value of EffectiveBasePermissions (section 3.2.5.87.1.1.2). 
     * If the list is in read-only UI mode, the value of EffectiveBasePermissionsForUI 
     * MUST be a subset of the value of EffectiveBasePermissions.It MUST 
     * NOT be NULL. 
     * @return BasePermissions
     */
    public function getEffectiveBasePermissionsForUI()
    {
        return $this->getProperty("EffectiveBasePermissionsForUI", new BasePermissions());
    }
    /**
     * Specifies 
     * the effective base permissions for the current user, as they SHOULD be 
     * displayed in the user interface (UI).<61> If the 
     * list is not in read-only UI mode, the value of EffectiveBasePermissionsForUI 
     * MUST be the same as the value of EffectiveBasePermissions (section 3.2.5.87.1.1.2). 
     * If the list is in read-only UI mode, the value of EffectiveBasePermissionsForUI 
     * MUST be a subset of the value of EffectiveBasePermissions.It MUST 
     * NOT be NULL. 
     * @var BasePermissions
     */
    public function setEffectiveBasePermissionsForUI($value)
    {
        $this->setProperty("EffectiveBasePermissionsForUI", $value, true);
    }
    /**
     * Specifies 
     * whether the list item is a file 
     * or a list 
     * folder. It MUST be 
     * one of the following values: File or Folder.
     * @return integer
     */
    public function getFileSystemObjectType()
    {
        return $this->getProperty("FileSystemObjectType");
    }
    /**
     * Specifies 
     * whether the list item is a file 
     * or a list 
     * folder. It MUST be 
     * one of the following values: File or Folder.
     * @var integer
     */
    public function setFileSystemObjectType($value)
    {
        $this->setProperty("FileSystemObjectType", $value, true);
    }
    /**
     * This is an 
     * overlay icon for the item. If the parent list of the item does not already have 
     * the IconOverlay field and The user setting the property does not have rights to 
     * add the field to the list then the property will not be set for the item.
     * @return string
     */
    public function getIconOverlay()
    {
        return $this->getProperty("IconOverlay");
    }
    /**
     * This is an 
     * overlay icon for the item. If the parent list of the item does not already have 
     * the IconOverlay field and The user setting the property does not have rights to 
     * add the field to the list then the property will not be set for the item.
     * @var string
     */
    public function setIconOverlay($value)
    {
        $this->setProperty("IconOverlay", $value, true);
    }
    /**
     * Specifies 
     * the list 
     * item identifier. It MUST be 0 for a list item in an external 
     * list. It MUST be -1 for list items that have not yet been added to a 
     * list.
     * @return integer
     */
    public function getId()
    {
        return $this->getProperty("Id");
    }
    /**
     * Specifies 
     * the list 
     * item identifier. It MUST be 0 for a list item in an external 
     * list. It MUST be -1 for list items that have not yet been added to a 
     * list.
     * @var integer
     */
    public function setId($value)
    {
        $this->setProperty("Id", $value, true);
    }
    /**
     * Returns 
     * the path for previewing a document in the browser, often in an interactive way, 
     * if that feature exists.
     * @return string
     */
    public function getServerRedirectedEmbedUri()
    {
        return $this->getProperty("ServerRedirectedEmbedUri");
    }
    /**
     * Returns 
     * the path for previewing a document in the browser, often in an interactive way, 
     * if that feature exists.
     * @var string
     */
    public function setServerRedirectedEmbedUri($value)
    {
        $this->setProperty("ServerRedirectedEmbedUri", $value, true);
    }
    /**
     * Returns 
     * the URL for previewing a document in the browser, often in an interactive way, 
     * if that feature exists. This is currently used in the hovering panel of search 
     * results and document library.
     * @return string
     */
    public function getServerRedirectedEmbedUrl()
    {
        return $this->getProperty("ServerRedirectedEmbedUrl");
    }
    /**
     * Returns 
     * the URL for previewing a document in the browser, often in an interactive way, 
     * if that feature exists. This is currently used in the hovering panel of search 
     * results and document library.
     * @var string
     */
    public function setServerRedirectedEmbedUrl($value)
    {
        $this->setProperty("ServerRedirectedEmbedUrl", $value, true);
    }
    /**
     * Gets the 
     * title of the item.
     * @return string
     */
    public function getClient_Title()
    {
        return $this->getProperty("Client_Title");
    }
    /**
     * Gets the 
     * title of the item.
     * @var string
     */
    public function setClient_Title($value)
    {
        $this->setProperty("Client_Title", $value, true);
    }
    /**
     * Specifies 
     * the content 
     * type of the list item. It MUST 
     * be NULL if the list item has no content type.
     * @return ContentType
     */
    public function getContentType()
    {
        return $this->getProperty("ContentType",
            new ContentType($this->getContext(),new ResourcePath("ContentType", $this->getResourcePath())));
    }
    /**
     * @return DlpPolicyTip
     */
    public function getGetDlpPolicyTip()
    {
        return $this->getProperty("GetDlpPolicyTip",
            new DlpPolicyTip($this->getContext(),new ResourcePath("GetDlpPolicyTip", $this->getResourcePath())));
    }
    /**
     * @return FieldStringValues
     */
    public function getFieldValuesAsHtml()
    {
        return $this->getProperty("FieldValuesAsHtml",
            new FieldStringValues($this->getContext(),
                new ResourcePath("FieldValuesAsHtml", $this->getResourcePath())));
    }
    /**
     * @return FieldStringValues
     */
    public function getFieldValuesAsText()
    {
        return $this->getProperty("FieldValuesAsText",
            new FieldStringValues($this->getContext(),
                new ResourcePath("FieldValuesAsText", $this->getResourcePath())));
    }
    /**
     * @return FieldStringValues
     */
    public function getFieldValuesForEdit()
    {
        return $this->getProperty("FieldValuesForEdit",
            new FieldStringValues($this->getContext(),
                new ResourcePath("FieldValuesForEdit", $this->getResourcePath())));
    }


    /**
     * @return ServerTypeInfo
     */
    public function getServerTypeInfo()
    {
        return ServerTypeInfo::fromFullName($this->typeName);
    }


    public function setProperty($name, $value, $persistChanges = true)
    {
        if ($value instanceof  FieldMultiLookupValue){
            parent::setProperty("{$name}Id", $value, true);
            parent::setProperty($name, $value, false);
        }
        elseif($value instanceof FieldLookupValue){
            parent::setProperty("{$name}Id", $value->LookupId, true);
            parent::setProperty($name, $value, false);
        }
        elseif($value instanceof TaxonomyFieldValue || $value instanceof TaxonomyFieldValueCollection){
           $this->setTaxonomyFieldValue($name, $value);
        }
        else{
            parent::setProperty($name, $value, $persistChanges);
        }
        if($name == "Id" && $this->resourcePath instanceof  EntityPath){
            $this->resourcePath->setKey($value);
        }
        return $this;
    }


    /**
     * @param string $name
     * @param string $value
     */
    private function setTaxonomyFieldValue($name, $value){
        $taxField = $this->getParentList()->getFields()->getByInternalNameOrTitle($name);
        $taxField->ensureProperty("TextField", function () use ($taxField, $value){
            $taxTextField = $this->getParentList()->getFields()->getById($taxField->getProperty("TextField"));
            $taxTextField->ensureProperty("StaticName", function () use($taxTextField, $value){
                $this->setProperty($taxTextField->getStaticName(), (string)$value);
                $this->update();
            });
        });
    }

    /**
     * Sets field values and creates a new version if versioning is enabled for the list
     * @return ListItem
     */
    public function update()
    {
        $this->ensureTypeName($this->getParentList());
        return parent::update();
    }


    /**
     * Updates the database with changes made to the list item.
     * @return $this
     */
    public function systemUpdate(){
        $qry = new InvokePostMethodQuery($this, "SystemUpdate");
        $this->getContext()->addQuery($qry);
        return $this;
    }


    /**
     * Updates the item without creating another version of the item.
     * @return $this
     */
    public function updateOverwriteVersion(){
        $qry = new InvokePostMethodQuery($this, "UpdateOverwriteVersion");
        $this->getContext()->addQuery($qry);
        return $this;
    }

    /**
     * Validates and sets the values of the specified collection of fields (2) for the list item and,
     * if successfully validated, commits all changes. Returns the modified list of values
     * with updated exception information.
     * @param array $formValues Specifies a collection of field internal names and values for the given field (2).
     * @param bool $newDocumentUpdate
     * @param string|null $checkInComment
     * @param bool $datesInUTC
     * @return ClientResult
     */
    public function validateUpdateListItem($formValues,
                                           $newDocumentUpdate=false,
                                           $checkInComment=null,
                                           $datesInUTC=false,
                                           $numberInInvariantCulture=false)
    {

        $normalizedFormValues = array_map(function ($k, $v) {
                return new ListItemFormUpdateValue($k, $v);
            }, array_keys($formValues), array_values($formValues));

        $payload = array(
            "formValues" => $normalizedFormValues,
            "bNewDocumentUpdate" => $newDocumentUpdate,
            "checkInComment" => $checkInComment,
            "datesInUTC" => $datesInUTC,
            "numberInInvariantCulture" => $numberInInvariantCulture
        );
        $result = new ClientResult($this->getContext(),
            new ClientValueCollection(ListItemFormUpdateValue::class));
        $qry = new InvokePostMethodQuery($this, "ValidateUpdateListItem",
            null, null, $payload);
        $this->getContext()->addQueryAndResultObject($qry, $result);
        return $result;
    }


    /**
     * @var string
     */
    protected $typeName;
}