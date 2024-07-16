import { registerBlockType } from '@wordpress/blocks'
import { useBlockProps, PanelColorSettings, InspectorControls } from '@wordpress/block-editor'
import {__} from '@wordpress/i18n'
import block from './block.json'
import icons from '../../icons.js'
import './main.css'

registerBlockType(block.name, {
    icon: icons.primary,
    edit({ attributes, setAttributes }) {
        const { bgColor, textColor } = attributes
        const blockProps = useBlockProps({
            className: 'wp-block-therosehill-plus-search-form',
            style: {
                'background-color': bgColor,
                color: textColor
            }
        })

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
                    <h1>Search: Your search term here</h1>
                    <form>
                        <input type="text" placeholder="Search" />
                        <div className="btn-wrapper">
                            <button type="submit" style={{
                                'background-color': bgColor,
                                color: textColor
                            }}>Search</button>
                        </div>
                    </form>
                </div>
              </>
        );
    }
})