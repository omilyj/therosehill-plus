import { registerBlockType } from '@wordpress/blocks'
import { useBlockProps, PanelColorSettings, InspectorControls } from '@wordpress/block-editor'
import {__} from '@wordpress/i18n'
import block from './block.json'
import icons from '../../icons.js'
import './main.css'

registerBlockType(block.name, {
    icon: icons.primary,
    edit({ attributes, setAttributes }) {
        const { bgColor, iconColor, borderColor } = attributes
        const blockProps = useBlockProps({
            className: 'wp-block-therosehill-plus-menu-search',
            style: {
                'background-color': bgColor,
                color: iconColor,
                'border-color': borderColor
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
                                label: __('Icon Color', 'therosehill-plus'),
                                value: iconColor,
                                onChange: iconColor => setAttributes({ iconColor })
                             },
                             {
                                label: __('Border Color', 'therosehill-plus'),
                                value: borderColor,
                                onChange: borderColor => setAttributes({ borderColor })
                             }
                        ]}
                    />
                </InspectorControls>
                <div {...blockProps}>
                    <form>
                        <button type="submit" style={{
                            'background-color': bgColor
                        }}>
                            <i class="bi bi-search" style={{
                            'color': iconColor
                        }}></i>
                        </button>
                        <input type="search" placeholder="Search" style={{
                            'border-color': borderColor
                        }} />
                    </form>
                </div>
              </>
        );
    }
})