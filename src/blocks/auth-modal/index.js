import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import icons from '../../icons.js'
import './main.css'

registerBlockType('therosehill-plus/auth-modal', {
    icon: icons.primary,
    edit({ attributes, setAttributes }) {
        const { showRegister } = attributes;

        const blockProps = useBlockProps({
            className: 'wp-block-udemy-plus-auth-modal'
        });

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('General', 'therosehill-plus')}>
                        <ToggleControl
                            label={__('Show Register', 'therosehill-plus')}
                            help={
                                showRegister ?
                                __('Showing registration form', 'therosehill-plus') :
                                __('Hiding registration form', 'therosehill-plus')
                            }
                            checked={showRegister}
                            onChange={showRegister => setAttributes({ showRegister })}
                        />
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    {__('This block is not previewable from the editor. View your site for a live demo.', 'therosehill-plus')}
                </div>
            </>
        );
    }
});