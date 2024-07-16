import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import icons from '../../icons.js'
import './main.css'

registerBlockType('therosehill-plus/header-tools', {
    icon: icons.primary,
    edit({ attributes, setAttributes }) {
        const { showAuth } = attributes

        const blockProps = useBlockProps({
            className: 'wp-block-udemy-plus-header-tools'
        });

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('General', 'therosehill-plus')}>
                        <ToggleControl
                            label={__('Show Login/Register link', 'therosehill-plus')}
                            checked={showAuth}
                            onChange={ showAuth => setAttributes({ showAuth })}
                            help={
                                showAuth ?
                                __('Showing Login/Register link', 'therosehill-plus') :
                                __('Hiding Login/Register link', 'therosehill-plus')
                            }
                        />
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    {
                        showAuth ?
                        <a className="signin-link open-modal" href="#">
                            <div className="signin-icon">
                                <i className="bi bi-person-circle"></i>
                            </div>
                            <div className="signin-text">
                                <small>Hello, Sign in</small>
                                My Account
                            </div>
                        </a> :
                        null
                    }
                </div>
            </>
        );
    }
});