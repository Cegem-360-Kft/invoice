<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string|null $nev
 * @property string|null $sku
 * @property string|null $ean
 * @property string|null $price
 * @property string|null $price_kivitelezok
 * @property string|null $price_kp_elore_harminc
 * @property string|null $price_kp_elore_huszonot
 * @property int|null $storage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\WoocommerceProductVariation|null $woocomerceProductVariation
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereEan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereNev($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePriceKivitelezok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePriceKpEloreHarminc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePriceKpEloreHuszonot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $wordpress_id
 * @property string|null $name
 * @property string|null $sku
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WoocommerceProductVariation> $woocommerceProductVariation
 * @property-read int|null $woocommerce_product_variation_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProduct whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProduct whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProduct whereWordpressId($value)
 */
	class WoocommerceProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $wordpress_id
 * @property string|null $name
 * @property string|null $sku
 * @property int|null $woocommerce_product_id
 * @property int|null $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\WoocommerceProduct|null $woocomerceProduct
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation whereWoocommerceProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WoocommerceProductVariation whereWordpressId($value)
 */
	class WoocommerceProductVariation extends \Eloquent {}
}

