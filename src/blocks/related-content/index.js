import { registerBlockType } from "@wordpress/blocks";
import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import icons from "../../icons.js";
import "./main.css";

registerBlockType("therosehill-plus/related-content", {
    icon: {
        src: icons.primary,
    },
    edit() {

        const blockProps = useBlockProps();

        return (
            <>
                <div {...blockProps}>
                    <InnerBlocks
                        orientation="vertical"
                        allowedBlocks={[
                            "core/heading",
                            "core/group",
                            "therosehill-plus/all-record-releases",
                            "therosehill-plus/all-residencies",
                            "therosehill-plus/events"
                        ]}
                        template={[
                            ["therosehill-plus/all-record-releases"],
                            ["therosehill-plus/all-residencies"],
                            ["therosehill-plus/events"],
                        ]}
                    // templateLock="insert"
                    />
                </div>
            </>
        );
    },
    save() {

        const blockProps = useBlockProps.save();

        return (
            <div {...blockProps}>
                <InnerBlocks.Content />
            </div>
        );
    },
});
