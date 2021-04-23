<?php
$pluginName = Mollie_WC_Plugin::PLUGIN_ID;
return [
    [
        'id' => $pluginName . '_' . 'title',
        'title' => __(
            'PayPay button display settings',
            'mollie-payments-for-woocommerce'
        ),
        'type' => 'title',
        'description' => '<p>' . __(
                'The PayPal button is optimised for digital goods.',
                'mollie-payments-for-woocommerce'
            ) . '</p>',
    ],
    'mollie_paypal_button_enabled_cart' => [
        'type' => 'checkbox',
        'title' => __(
            'Display on cart page',
            'mollie-payments-for-woocommerce'
        ),
        'description' => __(
            'Enable the PayPal button to be used in the cart page.',
            'mollie-payments-for-woocommerce'
        ),
        'default' => 'no',
    ],
    'mollie_paypal_button_enabled_product' => [
        'type' => 'checkbox',
        'title' => __(
            'Display on product page',
            'mollie-payments-for-woocommerce'
        ),
        'description' => __(
            'Enable the PayPal button to be used in the product page.',
            'mollie-payments-for-woocommerce'
        ),
        'default' => 'no',
    ],
    'color' => [
        'type' => 'select',
        'id' => 'mollie_paypal_buttton_color',
        'title' => _x('Button text and color', 'Mollie PayPal Button Settings', 'mollie-payments-for-woocommerce'),
        'description' => sprintf(
            _x(
                'Select the text and the colour of the button.',
                'Mollie PayPal Button Settings',
                'mollie-payments-for-woocommerce'
            )
        ),
        'default' => 'buy-gold',
        'options' => [
            'buy-gold' => _x('Buy with PayPal - Gold', 'Mollie PayPal button Settings', 'mollie-payments-for-woocommerce'),
            'checkout-gold' => _x('Checkout with PayPal - Gold', 'Mollie PayPal button Settings', 'mollie-payments-for-woocommerce'),
            'checkout-silver' => _x('Checkout with PayPal - Silver', 'Mollie PayPal button Settings', 'mollie-payments-for-woocommerce'),
        ],
    ],
    'language' => [
        'type' => 'select',
        'id' => 'mollie_paypal_buttton_language',
        'title' => _x('Translate button text into:', 'Mollie PayPal Button Settings', 'mollie-payments-for-woocommerce'),
        'description' => sprintf(
            _x(
                'Choose the language for the text in the button.',
                'Mollie PayPal Button Settings',
                'mollie-payments-for-woocommerce'
            )
        ),
        'default' => 'en',
        'options' => [
            'en' => _x('English', 'Mollie PayPal button Settings', 'mollie-payments-for-woocommerce'),
            'nl' => _x('Dutch', 'Mollie PayPal button Settings', 'mollie-payments-for-woocommerce'),
            'fr' => _x('French', 'Mollie PayPal button Settings', 'mollie-payments-for-woocommerce'),
            'de' => _x('German', 'Mollie PayPal button Settings', 'mollie-payments-for-woocommerce'),
            'pl' => _x('Polish', 'Mollie PayPal button Settings', 'mollie-payments-for-woocommerce'),
        ],

    ],
    'mollie_paypal_button_minimum_amount' => [
        'type' => 'number',
        'title' => __(
            'Minimum amount to display button',
            'mollie-payments-for-woocommerce'
        ),
        'description' => __(
            'If the product or the cart total amount is under this number, then the button will not show up.',
            'mollie-payments-for-woocommerce'
        ),
        'custom_attributes'=>['step'=>'0.01', 'min'=>'0', 'max'=>'100000000'],
        'default' => 0,
        'desc_tip' => true,
    ],
    [
        'id' => $pluginName . '_' . 'title',
        'title' => __(
            'PayPay button shipping costs',
            'mollie-payments-for-woocommerce'
        ),
        'type' => 'title',
        'description' => '<p>' . __(
                'A disadvantage of the PayPal button is that it does not allow automatic calculation of shipping costs and taxes for physical goods shipped to a specific delivery address. 
The settings below enable you to specify additional costs manually. ',
                'mollie-payments-for-woocommerce'
            ) . '</p>',
    ],
    'mollie_paypal_button_no_fee_amount' => [
        'type' => 'number',
        'title' => __(
            'Amount for free shipping',
            'mollie-payments-for-woocommerce'
        ),
        'description' => __(
            'If the product or the cart total amount is over this number, then the fixed fee will not apply.',
            'mollie-payments-for-woocommerce'
        ),
        'default' => 0,
        'custom_attributes'=>['step'=>'0.01', 'min'=>'0', 'max'=>'100000000'],
        'desc_tip' => true,
    ],
    'mollie_paypal_button_fixed_shipping_amount' => [
        'type' => 'number',
        'title' => __(
            'Fixed shipping fee amount',
            'mollie-payments-for-woocommerce'
        ),
        'description' => __(
            'All PayPal Button transactions must have shipping costs set to this flat rate. The flat rate will not apply to products that need no shipping, like virtual products.',
            'mollie-payments-for-woocommerce'
        ),
        'default' => 'no',
        'custom_attributes'=>['step'=>'0.01', 'min'=>'0', 'max'=>'100000000']
    ],



];
