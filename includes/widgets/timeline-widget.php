<?php
/**
 * Dope Timeline Widget for Elementor.
 *
 * @package DopeTimeline
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dope Timeline Widget Class.
 */
class DopeTimeline_Widget extends \Elementor\Widget_Base
{

    /**
     * Get Widget Name.
     *
     * @return string
     */
    public function get_name()
    {
        return 'dope_timeline';
    }

    /**
     * Get Widget Title.
     *
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Dope Timeline', 'dope-timeline');
    }

    /**
     * Get Widget Icon.
     *
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-time-line';
    }

    /**
     * Get Widget Categories.
     *
     * @return array
     */
    public function get_categories()
    {
        return ['dope-category'];
    }

    /**
     * Get Script Dependencies.
     *
     * @return array
     */
    public function get_script_depends()
    {
        return ['dopetimeline-script'];
    }

    /**
     * Get Style Dependencies.
     *
     * @return array
     */
    public function get_style_depends()
    {
        return ['dopetimeline-style'];
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Timeline Content', 'dope-timeline'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title/Role', 'dope-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Executive Board Member', 'dope-timeline'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'organization',
            [
                'label' => esc_html__('Organization', 'dope-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('International Water Resources Association (IWRA)', 'dope-timeline'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'date',
            [
                'label' => esc_html__('Date/Period', 'dope-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('2022-Present', 'dope-timeline'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'logo',
            [
                'label' => esc_html__('Choose Logo', 'dope-timeline'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'description_points',
            [
                'label' => esc_html__('Description Points', 'dope-timeline'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => '<ul><li>Point one</li><li>Point two</li></ul>',
                'description' => esc_html__('Enter points using a bullet list in the editor.', 'dope-timeline'),
            ]
        );

        $this->add_control(
            'timeline_items',
            [
                'label' => esc_html__('Timeline Items', 'dope-timeline'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Executive Board Member', 'dope-timeline'),
                        'organization' => esc_html__('International Water Resources Association (IWRA)', 'dope-timeline'),
                        'date' => esc_html__('2022-Present', 'dope-timeline'),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        // Style Tab.
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Global Styling', 'dope-timeline'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => esc_html__('Primary Color', 'dope-timeline'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0f52ba',
                'selectors' => [
                    '{{WRAPPER}} .timeline-org' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .timeline-dot' => 'background: linear-gradient(135deg, {{VALUE}} 0%, #3b82f6 100%);',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Widget Output.
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['timeline_items'])) {
            return;
        }
        ?>
        <div class="timeline-container">
            <?php foreach ($settings['timeline_items'] as $item) : ?>
                <div class="timeline-item">
                    <div class="timeline-logo">
                        <?php if (!empty($item['logo']['url'])) : ?>
                            <img src="<?php echo esc_url($item['logo']['url']); ?>" alt="<?php echo esc_attr($item['organization']); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <h3 class="timeline-title"><?php echo esc_html($item['title']); ?>,</h3>
                            <h3 class="timeline-org"> <?php echo esc_html($item['organization']); ?></h3>
                            <span class="timeline-date"><?php echo esc_html($item['date']); ?></span>
                        </div>
                        <?php if (!empty($item['description_points'])) : ?>
                            <div class="timeline-description">
                                <?php echo wp_kses_post($item['description_points']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Render Widget Output in Editor.
     */
    protected function content_template()
    {
        ?>
        <# if ( settings.timeline_items.length ) { #>
            <div class="timeline-container">
                <# _.each( settings.timeline_items, function( item ) { #>
                    <div class="timeline-item visible">
                        <div class="timeline-logo">
                            <# if ( item.logo.url ) { #>
                                <img src="{{{ item.logo.url }}}" alt="{{{ item.organization }}}">
                            <# } #>
                        </div>
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h3 class="timeline-title">{{{ item.title }}},</h3>
                                <h3 class="timeline-org"> {{{ item.organization }}}</h3>
                                <span class="timeline-date">{{{ item.date }}}</span>
                            </div>
                            <# if ( item.description_points ) { #>
                                <div class="timeline-description">
                                    {{{ item.description_points }}}
                                </div>
                            <# } #>
                        </div>
                    </div>
                <# } ); #>
            </div>
        <# } #>
        <?php
    }
}
