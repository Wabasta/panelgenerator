<?php
namespace Wabasta\PanelBuilder\Fields;

class FieldsDescriber
{
    /**
     * Default PanelBuilder field types
     * @return array
     */
    public static function types()
    {
        return [
            'text'         => 'Text field',
            'email'        => 'Email field',
            'textarea'     => 'Long text field',
            'radio'        => 'Radio',
            'checkbox'     => 'Checkbox',
            'date'         => 'Date picker',
            'datetime'     => 'Date and time picker',
            'relationship' => 'Relationship',
            'file'         => 'File field',
            'photo'        => 'Photo field',
            'password'     => 'Password field (hashed)',
            'money'        => 'Money',
            'enum'         => 'ENUM',
        ];
    }

    /**
     * Default PanelBuilder field validation types
     * @return array
     */
    public static function validation()
    {
        return [
            'optional'        => trans('panelbuilder::strings.optional'),
            'required'        => trans('panelbuilder::strings.required'),
            'required|unique' => trans('panelbuilder::strings.required_unique')
        ];
    }

    /**
     * Set fields to be nullable by default if validation is not in this array
     * @return array
     */
    public static function nullables()
    {
        return [
            'optional',
        ];
    }

    /**
     * Default PanelBuilder field types for migration
     * @return array
     */
    public static function migration()
    {
        return [
            'text'         => 'string("$FIELDNAME$")',
            'email'        => 'string("$FIELDNAME$")',
            'textarea'     => 'text("$FIELDNAME$")',
            'radio'        => 'string("$FIELDNAME$")',
            'checkbox'     => 'tinyInteger("$FIELDNAME$")->default($STATE$)',
            'date'         => 'date("$FIELDNAME$")',
            'datetime'     => 'dateTime("$FIELDNAME$")',
            'relationship' => 'integer("$RELATIONSHIP$_id")->references("id")->on("$RELATIONSHIP$")',
            'file'         => 'string("$FIELDNAME$")',
            'photo'        => 'string("$FIELDNAME$")',
            'password'     => 'string("$FIELDNAME$")',
            'money'        => 'decimal("$FIELDNAME$", 15, 2)',
            'enum'         => 'enum("$FIELDNAME$", [$VALUES$])',
        ];
    }

    /**
     * Default PanelBuilder state for checkbox
     * @return array
     */
    public static function default_cbox()
    {
        return [
            'false' => trans('panelbuilder::strings.default_unchecked'),
            'true'  => trans('panelbuilder::strings.default_checked'),
        ];
    }
}
