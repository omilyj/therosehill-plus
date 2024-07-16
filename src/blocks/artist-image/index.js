import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import icons from '../../icons.js';
import './main.css';

registerBlockType('therosehill-plus/artist-image', {
    icon: {
        src: icons.primary
    },
    edit() {
        const blockProps = useBlockProps();

        return (
            <>
                <div {...blockProps}>
                    <p>{__('If applicable, artist image appears here on the front-end', 'therosehill-plus')}</p>
                </div>
            </>
        );
    }
});