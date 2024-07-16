import { registerBlockType } from '@wordpress/blocks'
import { RichText, useBlockProps, PanelColorSettings, InspectorControls } from '@wordpress/block-editor'
import { __ } from '@wordpress/i18n'
import { PanelBody, TextControl } from '@wordpress/components';
import block from './block.json'
import icons from '../../icons.js'
import './main.css'

registerBlockType(block.name, {
    icon: icons.primary,
    edit({ attributes, setAttributes }) {
        const { ledText, gradientOne, gradientTwo, textColor } = attributes
        const blockProps = useBlockProps({
            className: 'wp-block-therosehill-plus-led-sign',
        })

        return (
            <>
                <InspectorControls>
                    <PanelBody>
                        <TextControl
                            label={__('Text', 'therosehill-plus')}
                            help={__('The text that will appear on the LED sign.', 'therosehill-plus')}
                            value={ledText}
                            onChange={ledText => setAttributes({ ledText })}
                            placeholder={__('E.g. Coming up at The Rose Hill...', 'therosehill-plus')}
                        />
                    </PanelBody>
                    <PanelColorSettings
                        title={__('Colors', 'therosehill-plus')}
                        colorSettings={[
                            {
                                label: __('Background Gradient Color 1', 'therosehill-plus'),
                                value: gradientOne,
                                onChange: gradientOne => setAttributes({ gradientOne }),
                                colors: [
                                    {
                                        slug: 'led-grad-one',
                                        name: 'LED Gradient 1',
                                        color: '#EEC61F'
                                    },
                                ]
                            },
                            {
                                label: __('Background Gradient Color 2', 'therosehill-plus'),
                                value: gradientTwo,
                                onChange: gradientTwo => setAttributes({ gradientTwo }),
                                colors: [
                                    {
                                        slug: 'led-grad-two',
                                        name: 'LED Gradient 2',
                                        color: '#F0C114'
                                    },
                                ]
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
                    <div className="led-sign">
                        <span className="vl-1"></span>
                        <span className="vl-2"></span>
                        <div className="scrolling_text" style={{
                            backgroundImage: `radial-gradient(circle at center, ${gradientOne} 0.15rem, transparent 0), radial-gradient(circle at center, ${gradientTwo} 0.15rem, transparent 0)`
                        }}>
                            <div className="text" style={{
                                color: textColor
                            }}>
                                {[...Array(10)].map((_, index) => (
                                    <span key={index}>{ledText}</span>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </>
        )
    },
    save({ attributes }) {
        const { ledText, gradientOne, gradientTwo, textColor } = attributes
        const blockProps = useBlockProps.save({
            className: 'wp-block-therosehill-plus-led-sign',
        })

        return (
            <div {...blockProps}>
                <div className="led-sign">
                    <span className="vl-1"></span>
                    <span className="vl-2"></span>
                    <div className="scrolling_text" style={{
                        backgroundImage: `radial-gradient(circle at center, ${gradientOne} 0.15rem, transparent 0), radial-gradient(circle at center, ${gradientTwo} 0.15rem, transparent 0)`
                    }}>
                        <div className="text" style={{
                            color: textColor
                        }}>
                            {[...Array(10)].map((_, index) => (
                                <span key={index}>{ledText}</span>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        )
    }
})