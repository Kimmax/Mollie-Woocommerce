<?php

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\CurrentProfile;
use Mollie\WooCommerce\Components\ComponentsStyles;
use Mollie\WooCommerce\Plugin;
use Mollie\WooCommerce\SDK\Api;
use Mollie\WooCommerce\Settings\SettingsComponents;

/**
 * Check if the current page context is for checkout
 *
 * @return bool
 */
function mollieWooCommerceIsCheckoutContext()
{
    return is_checkout() || is_checkout_pay_page();
}

/**
 * ComponentsStyles Factory
 *
 * @return array
 */
function mollieWooCommerceComponentsStylesForAvailableGateways()
{
    $pluginPath = untrailingslashit(M4W_PLUGIN_DIR) . '/';

    $mollieComponentsStyles = new ComponentsStyles(
        new SettingsComponents($pluginPath),
        WC()->payment_gateways()
    );

    return $mollieComponentsStyles->forAvailableGateways();
}

/**
 * Is Mollie Test Mode enabled?
 *
 * @return bool
 */
function mollieWooCommerceIsTestModeEnabled()
{
    $settingsHelper = Plugin::getSettingsHelper();
    $isTestModeEnabled = $settingsHelper->isTestModeEnabled();

    return $isTestModeEnabled;
}

/**
 * If we are calling this the api key has been updated, we need a new api object
 * to retrieve a new profile id
 *
 * @return CurrentProfile
 * @throws ApiException
 */
function mollieWooCommerceMerchantProfile()
{
    $isTestMode = mollieWooCommerceIsTestModeEnabled();

    $apiHelper = new Api(
        Plugin::getSettingsHelper()
    );

    return $apiHelper->getApiClient(
        $isTestMode,
        true
    )->profiles->getCurrent();
}

/**
 * Retrieve the merchant profile ID
 *
 * @return int|string
 * @throws ApiException
 */
function mollieWooCommerceMerchantProfileId()
{
    static $merchantProfileId = null;
    $merchantProfileIdOptionKey = Plugin::PLUGIN_ID . '_profile_merchant_id';

    if ($merchantProfileId === null) {
        $merchantProfileId = get_option($merchantProfileIdOptionKey, '');

        /*
         * Try to retrieve the merchant profile ID from an Api Request if not stored already,
         * then store it into the database
         */
        if (!$merchantProfileId) {
            try {
                $merchantProfile = mollieWooCommerceMerchantProfile();
                $merchantProfileId = isset($merchantProfile->id) ? $merchantProfile->id : '';
            } catch (ApiException $exception) {
                $merchantProfileId = '';
            }

            if ($merchantProfileId) {
                update_option($merchantProfileIdOptionKey, $merchantProfileId);
            }
        }
    }

    return $merchantProfileId;
}

/**
 * Retrieve the cardToken value for Mollie Components
 *
 * @return string
 */
function mollieWooCommerceCardToken()
{
    return $cardToken = filter_input(INPUT_POST, 'cardToken', FILTER_SANITIZE_STRING) ?: '';
}

/**
 * Retrieve the available Payment Methods Data
 *
 * @return array|bool|mixed|\Mollie\Api\Resources\BaseCollection|\Mollie\Api\Resources\MethodCollection
 */
function mollieWooCommerceAvailablePaymentMethods()
{
    $testMode = mollieWooCommerceIsTestModeEnabled();
    $dataHelper = Plugin::getDataHelper();
    $methods = $dataHelper->getApiPaymentMethods($testMode, $use_cache = true);

    return $methods;
}

/**
 * Isolates static getDataHelper calls.
 *
 * @return \Mollie\WooCommerce\Utils\Data
 */
function mollieWooCommerceGetDataHelper()
{
    return Plugin::getDataHelper();
}

/**
 * Check if certain gateway setting is enabled.
 *
 * @param $gatewaySettingsName string
 * @param $settingToCheck string
 * @param bool $default
 * @return bool
 */
function mollieWooCommerceIsGatewayEnabled($gatewaySettingsName, $settingToCheck, $default = false)
{

    $gatewaySettings = get_option($gatewaySettingsName);
    return mollieWooCommerceStringToBoolOption(
        checkIndexExistOrDefault($gatewaySettings, $settingToCheck, $default)
    );
}

/**
 * Check if the Apple Pay gateway is enabled and then if the button is enabled too.
 *
 * @param $page
 *
 * @return bool
 */
function mollieWooCommerceisApplePayDirectEnabled($page)
{
    $pageToCheck = 'mollie_apple_pay_button_enabled_'.$page;
    return mollieWooCommerceIsGatewayEnabled('mollie_wc_gateway_applepay_settings', $pageToCheck);
}
/**
 * Check if the PayPal gateway is enabled and then if the button is enabled too.
 *
 * @param $page string setting to check between cart or product
 *
 * @return bool
 */
function mollieWooCommerceIsPayPalButtonEnabled($page)
{
    $payPalGatewayEnabled = mollieWooCommerceIsGatewayEnabled('mollie_wc_gateway_paypal_settings', 'enabled');

    if (!$payPalGatewayEnabled) {
        return false;
    }
    $settingToCheck = 'mollie_paypal_button_enabled_'.$page;
    return mollieWooCommerceIsGatewayEnabled('mollie_wc_gateway_paypal_settings', $settingToCheck);
}

/**
 * Check if the product needs shipping
 *
 * @param $product
 *
 * @return bool
 */
function mollieWooCommerceCheckIfNeedShipping($product)
{
    if (!wc_shipping_enabled()
        || 0 === wc_get_shipping_method_count(
            true
        )
    ) {
        return false;
    }
    $needs_shipping = false;
    if ($product->is_type('variable')){
        return false;
    }

    if ($product->needs_shipping()) {
        $needs_shipping = true;
    }

    return $needs_shipping;
}

function checkIndexExistOrDefault($array, $key, $default)
{
    return isset($array[$key]) ? $array[$key] : $default;
}
/**
 * Check if the issuers dropdown for this gateway is enabled.
 *
 * @param string $gatewaySettingsName mollie_wc_gateway_xxxx_settings
 * @return bool
 */
function mollieWooCommerceIsDropdownEnabled($gatewaySettingsName)
{
    $gatewaySettings = get_option($gatewaySettingsName);
    $optionValue = checkIndexExistOrDefault($gatewaySettings, 'issuers_dropdown_shown', 'yes');
    return $optionValue == 'yes';
}

/**
 * Check if the Voucher gateway is enabled.
 *
 * @return bool
 */
function mollieWooCommerceIsVoucherEnabled(){
    $voucherSettings = get_option('mollie_wc_gateway_voucher_settings');
    if(!$voucherSettings){
        $voucherSettings = get_option('mollie_wc_gateway_mealvoucher_settings');
    }
    return $voucherSettings? ($voucherSettings['enabled'] == 'yes'): false;
}

/**
 * Check if is a Mollie gateway
 * @param $gateway string
 *
 * @return bool
*/
function mollieWooCommerceIsMollieGateway($gateway)
{
    if (strpos($gateway, 'mollie_wc_gateway_') !== false) {
        return true;
    }
    return false;
}


