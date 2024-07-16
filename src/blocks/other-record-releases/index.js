import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import icons from '../../icons.js';
import './main.css';

registerBlockType('therosehill-plus/other-record-releases', {
    icon: {
        src: icons.primary
    },
    edit() {
        const blockProps = useBlockProps();

        return (
            <>
                <div {...blockProps}>
                    <div className="label-releases">
                        <h3>{__('Other Releases', 'therosehill-plus')}</h3>
                        <ul>
                            <li>
                                <a>{__('If applicable, other releases automatically appear here on the front-end', 'therosehill-plus')}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </>
        );
    }
});