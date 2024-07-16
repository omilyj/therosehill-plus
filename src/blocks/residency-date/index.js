import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import icons from '../../icons.js';
import './main.css';

registerBlockType('therosehill-plus/residency-date', {
    icon: {
        src: icons.primary
    },
    edit({ attributes, setAttributes }) {
        const { textAlignment } = attributes
        const blockProps = useBlockProps();

        const alignmentClass = textAlignment ? `has-text-align-${textAlignment}` : '';

        const { meta } = useSelect((select) => {
            const { getCurrentPostAttribute } = select('core/editor');
            return {
                meta: getCurrentPostAttribute('meta'),
            };
        });

        const residencyStartDate = meta ? meta['residency_start_date'] : '';
        const residencyEndDate = meta ? meta['residency_end_date'] : '';

        return (
            <>
                <InspectorControls>
                    <BlockControls>
                        <AlignmentToolbar
                            value={textAlignment}
                            onChange={(textAlignment) => setAttributes({ textAlignment })}
                        />
                    </BlockControls>
                </InspectorControls>
                <div {...blockProps}>
                    <div className={`residency-meta-date ${alignmentClass}`}>
                        {residencyStartDate ? residencyStartDate : __('dd/mm/yyyy', 'therosehill-plus')} - {residencyEndDate ? residencyEndDate : __('dd/mm/yyyy', 'therosehill-plus')}
                    </div>
                </div>
            </>
        );
    }
});