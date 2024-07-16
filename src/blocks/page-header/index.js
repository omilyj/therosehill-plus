import { registerBlockType } from '@wordpress/blocks';
import { RichText, useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import icons from '../../icons.js'
import './main.css'

registerBlockType('therosehill-plus/page-header', {
  icon: icons.primary,
	edit({ attributes, setAttributes }) {
    const { content, showCategory } = attributes
    const blockProps = useBlockProps({
      className: 'wp-block-udemy-plus-page-header'
    });

    return (
      <>
        <InspectorControls>
          <PanelBody title={__('General', 'therosehill-plus')}>
            <ToggleControl
              label={__('Show Category', 'therosehill-plus')}
              checked={showCategory}
              onChange={ showCategory => setAttributes({ showCategory })}
              help={
                showCategory ?
                __('Category shown', 'therosehill-plus') :
                __('Custom Content shown', 'therosehill-plus')
              }
            />
          </PanelBody>
        </InspectorControls>
        <div {...blockProps}>
          <div className="inner-page-header">
            {
              showCategory ?
              <h1>{__('Category: Some Category', 'therosehill-plus')}</h1> :
              <RichText
                tagName="h1"
                placeholder={__("Heading", "therosehill-plus")}
                value={content}
                onChange={content => setAttributes({ content })}
              />
            }
          </div>
        </div>
      </>
    );
  }
});