import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, PanelColorSettings, InspectorControls, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import icons from '../../icons.js';
import './main.css';

registerBlockType('therosehill-plus/record-release-date', {
    icon: icons.primary,
    edit({ attributes, setAttributes }) {
        const { bgColor, textColor, comingSoonBg, comingSoonText, textAlignment } = attributes
        const blockProps = useBlockProps({
            className: 'wp-block-therosehill-plus-record-release-date',
            style: {
                'background-color': bgColor,
                color: textColor,
            }
        })

        const alignmentClass = textAlignment ? `has-text-align-${textAlignment}` : '';

        const { meta } = useSelect((select) => {
            const { getCurrentPostAttribute } = select('core/editor');
            return {
                meta: getCurrentPostAttribute('meta'),
            };
        });

        const releaseDate = meta ? meta['record_release_date'] : '';

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
                             },
                             {
                                label: __('Coming Soon Background Color', 'therosehill-plus'),
                                value: comingSoonBg,
                                onChange: comingSoonBg => setAttributes({ comingSoonBg })
                             },
                             {
                                label: __('Coming Soon Text Color', 'therosehill-plus'),
                                value: comingSoonText,
                                onChange: comingSoonText => setAttributes({ comingSoonText })
                             }
                        ]}
                    />
                    <BlockControls>
                        <AlignmentToolbar
                            value={textAlignment}
                            onChange={(textAlignment) => setAttributes({ textAlignment })}
                        />
				    </BlockControls>
                </InspectorControls>
                <div {...blockProps}>
                    <div className={`record-meta-date ${alignmentClass}`}>
                        {releaseDate ? releaseDate : __('dd/mm/yyyy', 'therosehill-plus')}
                    </div>
                </div>
            </>
        );
    }
});