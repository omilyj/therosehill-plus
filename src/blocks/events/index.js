import { registerBlockType } from '@wordpress/blocks'
import { useBlockProps, InspectorControls } from '@wordpress/block-editor'
import { __ } from '@wordpress/i18n'
import { PanelBody, QueryControls, RangeControl, SelectControl, ToggleControl } from '@wordpress/components'
import { RawHTML } from '@wordpress/element'
import { useSelect } from '@wordpress/data'
import icons from '../../icons.js'
import './main.css'

registerBlockType('therosehill-plus/events', {
    icon: {
        src: icons.primary
    },
    edit({ attributes, setAttributes }) {
        const { count, promoters, excerptLength, eventView, columns, hideShowFilter } = attributes
        const blockProps = useBlockProps();

        const promoterData = useSelect((select) => {
            return select("core").getEntityRecords(
                'taxonomy',
                'promoter',
                {
                    per_page: -1
                }
            );
        }, []);
        const suggestions = {};

        promoterData?.forEach(term => {
            suggestions[term.name] = term;
        });

        const eventTypeData = useSelect((select) => {
            return select('core').getEntityRecords(
                'taxonomy',
                'event-type',
                {
                    per_page: -1,
                }
            );
        }, []);

        const promoterOptions = promoterData?.map((term) => ({
            label: term.name,
            value: term.slug,
        }));

        const eventTypeOptions = eventTypeData?.map((type) => ({
            label: type.name,
            value: type.slug,
        }));

        const artistSlug = new URLSearchParams(window.location.search).get('artist');

        const promoterIDs = promoters.map((term) => term.id);

        const posts = useSelect((select) => {
            const baseQuery = {
                per_page: count,
                _embed: true,
                promoter: promoterIDs,
                order: "asc",
                orderByStartDate: 1,
            };

            // Only add the artist filter if artistSlug is present
            if (artistSlug) {
                baseQuery['filter[artist]'] = artistSlug; // Adjust based on actual taxonomy query structure
            }
        
            return select('core').getEntityRecords('postType', 'event', baseQuery);
        }, [count, promoterIDs, artistSlug]);

        function getOrdinalIndicator(day) {
            if (day > 3 && day < 21) return 'th';
            switch (day % 10) {
                case 1: return 'st';
                case 2: return 'nd';
                case 3: return 'rd';
                default: return 'th';
            }
        }
        function formatDate(dateString) {
            const date = new Date(dateString);
            const dayOfWeek = date.toLocaleDateString('en-GB', { weekday: 'short' });
            const month = date.toLocaleDateString('en-GB', { month: 'short' });
            const dayOfMonth = date.getDate();
            const ordinalIndicator = getOrdinalIndicator(dayOfMonth);
            return `${dayOfWeek} ${dayOfMonth}${ordinalIndicator} ${month}`;
        }

        function formatTime(timeString) {
            const [hours, minutes] = timeString.split(':').map(num => parseInt(num, 10));
            let period = 'am';
            let hour = hours;

            // Convert to 12-hour format
            if (hours >= 12) {
                period = 'pm';
                if (hours > 12) hour = hours - 12;
            }
            if (hour === 0) hour = 12; // Midnight to 12 am

            // Format minutes if they are not "00", else just show the hour and period
            const formattedMinutes = minutes !== 0 ? `:${minutes < 10 ? `0${minutes}` : minutes}` : '';
            return `${hour}${formattedMinutes}${period}`;
        }

        console.log(posts);

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Settings', 'therosehill-plus')}>
                        <SelectControl
                            label={__("Event View", "therosehill-plus")}
                            value={eventView}
                            options={[
                                { label: __('Grid View', 'therosehill-plus'), value: 'grid' },
                                { label: __('List View', 'therosehill-plus'), value: 'list' },
                                { label: __('Minimal', 'therosehill-plus'), value: 'minimal' }
                            ]}
                            onChange={(newVal) => setAttributes({ eventView: newVal })}
                        />
                        {eventView === 'grid' && (
                            <RangeControl
                                label={__("Columns", "therosehill-plus")}
                                onChange={(newColumns) => setAttributes({ columns: newColumns })}
                                value={columns}
                                min={2}
                                max={4}
                            />
                        )}
                        <ToggleControl
                            label={__("Event Filters", "therosehill-plus")}
                            help={hideShowFilter ? __("Event filters are visible.", "therosehill-plus") : __("Event filters are hidden.", "therosehill-plus")}
                            checked={hideShowFilter}
                            onChange={(newVal) => setAttributes({ hideShowFilter: newVal })}
                        />
                        <QueryControls
                            numberOfItems={count}
                            minItems={1}
                            maxItems={100}
                            onNumberOfItemsChange={(count) => setAttributes({ count })}
                            categorySuggestions={suggestions}
                            onCategoryChange={(newTerms) => {
                                const newPromoters = []

                                newTerms.forEach(promoter => {
                                    if (typeof promoter === 'object') {
                                        return newPromoters.push(promoter);
                                    }

                                    const promoterTerm = promoterData?.find(
                                        (term) => term.name === promoter
                                    );

                                    if (promoterTerm) newPromoters.push(promoterTerm)
                                });

                                setAttributes({ promoters: newPromoters });
                            }}
                            selectedCategories={promoters}
                        />
                        <RangeControl
                            label={__("Excerpt Length", "therosehill-plus")}
                            value={excerptLength}
                            onChange={newVal => setAttributes({ excerptLength: newVal })}
                            min={100}
                            max={400}
                        />
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    {hideShowFilter && (
                        <div className="event-filters">
                            <form>
                                <div className="input-wrapper">
                                    <div className="select-wrapper">
                                        <select name="dates">
                                            <option value="">Date Range</option>
                                            <option value="this-week">This Week</option>
                                            <option value="this-weekend">This Weekend</option>
                                            <option value="this-month">This Month</option>
                                            <option value="next-week">Next Week</option>
                                            <option value="next-weekend">Next Weekend</option>
                                            <option value="next-month">Next Month</option>
                                        </select>
                                        <button type="button" class="clear-field"><i class="bi bi-x"></i></button>
                                    </div>
                                </div>
                                <div className="input-wrapper">
                                    <input type="text" placeholder="Keyword" />
                                    <button type="button" class="clear-field"><i class="bi bi-x"></i></button>
                                </div>
                                <div className="input-wrapper">
                                    <div className="select-wrapper">
                                        <select name="promoter">
                                            <option value="">Promoter</option>
                                            {promoterOptions?.map((option) => (
                                                <option key={option.value} value={option.value}>
                                                    {option.label}
                                                </option>
                                            ))}
                                        </select>
                                        <button type="button" class="clear-field"><i class="bi bi-x"></i></button>
                                    </div>
                                </div>
                                <div className="input-wrapper">
                                    <div className="select-wrapper">
                                        <select name="event-type">
                                            <option value="">Event Type</option>
                                            {eventTypeOptions?.map((option) => (
                                                <option key={option.value} value={option.value}>
                                                    {option.label}
                                                </option>
                                            ))}
                                        </select>
                                        <button type="button" class="clear-field"><i class="bi bi-x"></i></button>
                                    </div>
                                </div>
                                <button type="submit">Search</button>
                                <button type="reset">Clear All</button>
                            </form>
                            <div className="event-view">
                                <span className="grid-view"><i className="bi bi-grid-3x3-gap"></i></span>
                                <span className="list-view"><i className="bi bi-list"></i></span>
                            </div>
                        </div>
                    )}
                    <div className={`event-listings ${eventView}-view-active cols-${eventView === 'grid' ? columns : ''}`}>
                        {posts?.map(post => {
                            const featuredImage =
                                post._embedded &&
                                post._embedded['wp:featuredmedia'] &&
                                post._embedded['wp:featuredmedia'].length > 0 &&
                                post._embedded['wp:featuredmedia'][0];

                            const promoterName =
                                post._embedded &&
                                post._embedded['wp:term'] &&
                                post._embedded['wp:term'][4] &&
                                post._embedded['wp:term'][4].length > 0 &&
                                post._embedded['wp:term'][4][0];

                            const eventType =
                                post._embedded &&
                                post._embedded['wp:term'] &&
                                post._embedded['wp:term'][3] &&
                                post._embedded['wp:term'][3].length > 0 &&
                                post._embedded['wp:term'][3][0];

                            const startDate =
                                post.meta &&
                                    post.meta.event_start_date ?
                                    formatDate(post.meta.event_start_date) : '';

                            const endDate =
                                post.meta &&
                                    post.meta.event_end_date ?
                                    formatDate(post.meta.event_end_date) : '';

                            const startTime =
                                post.meta &&
                                    post.meta.event_start_time ?
                                    formatTime(post.meta.event_start_time) : '';

                            const endTime =
                                post.meta &&
                                    post.meta.event_end_time ?
                                    formatTime(post.meta.event_end_time) : '';

                            const trimExcerpt = (excerpt) => {
                                if (excerpt.length <= excerptLength) return excerpt;
                                return excerpt.slice(0, excerptLength) + '...';
                            };

                            if (eventView === 'grid' || eventView === 'list') {
                                return (
                                    <div className="single-post">
                                        {featuredImage && (
                                            <span className="single-post-image">
                                                <img src={
                                                    featuredImage.media_details.sizes.thumbnail.source_url
                                                } alt={featuredImage.alt_text} />
                                                {eventType && (
                                                    <span className="event-type">
                                                        {eventType.name}
                                                    </span>
                                                )}
                                            </span>
                                        )}
                                        <div className="single-post-detail">
                                            <div className="event-content">
                                                {promoterName && (
                                                    <span className="promoter">
                                                        {promoterName.name} {__('presents', 'therosehill-plus')}
                                                    </span>
                                                )}
                                                <h2><RawHTML>{post.title.rendered}</RawHTML></h2>
                                                <p className="excerpt"><RawHTML>{trimExcerpt(post.excerpt.rendered)}</RawHTML></p>
                                            </div>
                                            <div className="event-extras">
                                                <span><i className="bi bi-calendar2-event"></i>{startDate}{endDate && ` - ${endDate}`}</span>
                                                <span><i className="bi bi-clock"></i>{startTime} - {endTime}</span>
                                                <span className="tickets"><i className="bi bi-ticket-perforated"></i>{post.meta.event_ticket_price}</span>
                                                <a className="button" href={post.link}>{__('Find out more', 'therosehill-plus')}</a>
                                            </div>
                                        </div>
                                    </div>
                                );
                            } else if (eventView === 'minimal') {
                                return (
                                    <div className="single-post">
                                        <div className="single-post-detail">
                                            <div className="event-content">
                                                {promoterName && (
                                                    <span className="promoter">
                                                        {promoterName.name} {__('presents', 'therosehill-plus')}
                                                    </span>
                                                )}
                                                <h2><RawHTML>{post.title.rendered}</RawHTML></h2>
                                            </div>
                                            <div className="event-extras">
                                                <span><i className="bi bi-calendar2-event"></i>{startDate}{endDate && ` - ${endDate}`}</span>
                                                <span><i className="bi bi-clock"></i>{startTime} - {endTime}</span>
                                                <span className="tickets"><i className="bi bi-ticket-perforated"></i>{post.meta.event_ticket_price}</span>
                                            </div>
                                        </div>
                                    </div>
                                );
                            }
                        })}
                    </div>
                </div>
            </>
        );
    }
});