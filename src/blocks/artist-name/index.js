import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import icons from '../../icons.js';
import './main.css';

registerBlockType('therosehill-plus/artist-name', {
    icon: {
        src: icons.primary
    },
    edit({ attributes, setAttributes }) {
        const { textAlignment } = attributes
        const blockProps = useBlockProps();

        const alignmentClass = textAlignment ? `has-text-align-${textAlignment}` : '';

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
                    <p className={`${alignmentClass}`}>
                        <span className="prefix">{__('by:', 'therosehill-plus')}</span> {__('Artist Name', 'therosehill-plus')}
                    </p>
                </div>
            </>
        );
    }
});