import { registerBlockType } from '@wordpress/blocks'
import { useBlockProps, PanelColorSettings, InspectorControls } from '@wordpress/block-editor'
import { __ } from '@wordpress/i18n'
import block from './block.json'
import icons from '../../icons.js'
import './main.css'

registerBlockType('therosehill-plus/record-links', {
    icon: icons.primary,
    edit({ attributes, setAttributes }) {
        const { bgColor, textColor } = attributes
        const blockProps = useBlockProps()

        return (
            <>
                <InspectorControls>
                    <PanelColorSettings
                        title={__('Colors', 'therosehill-plus')}
                        colorSettings={[
                            {
                                label: __('Background Color', 'therosehill-plus'),
                                value: bgColor,
                                onChange: bgColor => setAttributes({ bgColor })
                            },
                            {
                                label: __('Text Color', 'therosehill-plus'),
                                value: textColor,
                                onChange: textColor => setAttributes({ textColor })
                            }
                        ]}
                    />
                </InspectorControls>
                <div {...blockProps}>
                    <div className="record-meta-links">
                            <a className="button" style={{
                                'background-color': bgColor,
                                color: textColor
                            }}>
                                <i className="bi bi-ear-fill record-button-icon"></i>{__('Listen', 'therosehill-plus')}
                            </a>
                            <span className="button-space"></span>
                            <a className="button" style={{
                                'background-color': bgColor,
                                color: textColor
                            }}>
                                <i className="bi bi-bag-heart-fill record-button-icon"></i>{__('Buy', 'therosehill-plus')}
                            </a>
                    </div>
                </div>
                </>
                );
    }
})