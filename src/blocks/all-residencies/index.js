import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import icons from '../../icons.js';
import './main.css';

registerBlockType('therosehill-plus/all-residencies', {
    icon: {
        src: icons.primary
    },
    edit() {
        const blockProps = useBlockProps();

        return (
            <>
                <div {...blockProps}>
                    <div className="resident-residencies">
                        <h3>{__('Residencies at The Rose Hill', 'therosehill-plus')}</h3>
                        <ul>
                            <li>
                                <a>{__('If applicable, residencies automatically appear here on the front-end', 'therosehill-plus')}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </>
        );
    }
});