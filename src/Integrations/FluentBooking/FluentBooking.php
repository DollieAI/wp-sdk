<?php

namespace Dollie\SDK\Integrations\FluentBooking;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'FluentBooking',
    name: 'FluentBooking',
    slug: 'fluent-booking',
    since: '1.0.0'
)]
/**
 * FluentBooking core integrations file
 *
 * @since 1.0.0
 */
class FluentBooking extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'FluentBooking';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('FluentBooking', 'dollie');
        $this->description = __('FluentBooking is the Ultimate Scheduling Solution for WordPress. Harness the power of unlimited appointments, bookings, webinars, events, sales calls, etc., and save time with scheduling automation.', 'dollie');
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('FLUENT_BOOKING_VERSION');
    }
}

IntegrationsController::register(FluentBooking::class);
