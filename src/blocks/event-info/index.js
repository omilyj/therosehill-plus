import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import icons from '../../icons.js';
import './main.css';

registerBlockType('therosehill-plus/event-info', {
    icon: {
        src: icons.primary
    },
    edit() {
        const blockProps = useBlockProps();

        return (
            <>
                <div {...blockProps}>
                    <div className="event-meta">
                        <span className="event-type">{__('Live Music', 'therosehill-plus')}</span>
                        <span><i className="bi bi-calendar2-event"></i>{__('Saturday 26th July', 'therosehill-plus')}</span>
                        <span><i className="bi bi-clock"></i>{__('7pm - 11pm', 'therosehill-plus')}</span>
                        <span><i className="bi bi-ticket-perforated"></i>{__('£6 - £14', 'therosehill-plus')}</span>
                    </div>
                    <a className="button">{__('Get Tickets', 'therosehill-plus')}</a>
                    <div className='promoter-about'>
                        <h2 className='wp-block-heading has-rh-anonymous-pro-font-family has-medium-font-size'>
                            <span className='prefix'>{__('Promoter:', 'therosehill-plus')}</span> {__('Melting Vinyl', 'therosehill-plus')}
                        </h2>
                    </div>
                    <div className="location">
                        <h2 className="wp-block-heading has-rh-anonymous-pro-font-family has-medium-font-size">
                            {__('Location:', 'therosehill-plus')}
                        </h2>
                        <p>
                            {__('The Rose Hill, Rose Hill Terrace, Brighton, BN1 4JL', 'therosehill-plus')}
                        </p>
                    </div>
                </div>
            </>
        );
    }
});