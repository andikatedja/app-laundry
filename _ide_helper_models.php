<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PriceList[] $price_lists
 * @property-read int|null $price_lists_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ComplaintSuggestion
 *
 * @property int $id
 * @property string $body
 * @property string $type
 * @property int $user_id
 * @property string|null $reply
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintSuggestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintSuggestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintSuggestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintSuggestion whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintSuggestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintSuggestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintSuggestion whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintSuggestion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintSuggestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintSuggestion whereUserId($value)
 */
	class ComplaintSuggestion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Item
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PriceList[] $price_lists
 * @property-read int|null $price_lists_count
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUpdatedAt($value)
 */
	class Item extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PriceList
 *
 * @property int $id
 * @property int $item_id
 * @property int $category_id
 * @property int $service_id
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\Service $service
 * @property-read \App\Models\TransactionDetail|null $transaction_detail
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList query()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList whereUpdatedAt($value)
 */
	class PriceList extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Service
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PriceList[] $price_lists
 * @property-read int|null $price_lists_count
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ServiceType
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transaction
 * @property-read int|null $transaction_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereUpdatedAt($value)
 */
	class ServiceType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Status
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status query()
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereUpdatedAt($value)
 */
	class Status extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $status_id
 * @property int|null $service_type_id
 * @property int $admin_id
 * @property int $member_id
 * @property string|null $finish_date
 * @property int $service_cost
 * @property int $discount
 * @property int $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $payment_amount
 * @property-read \App\Models\User $admin
 * @property-read \App\Models\User $member
 * @property-read \App\Models\ServiceType|null $service_type
 * @property-read \App\Models\Status $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TransactionDetail[] $transaction_details
 * @property-read int|null $transaction_details_count
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePaymentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereServiceCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereServiceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TransactionDetail
 *
 * @property int $id
 * @property int $transaction_id
 * @property int $price_list_id
 * @property int $quantity
 * @property int $price
 * @property int $sub_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PriceList $price_list
 * @property-read \App\Models\Transaction $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail wherePriceListId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionDetail whereUpdatedAt($value)
 */
	class TransactionDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property \App\Enums\Role $role
 * @property string|null $gender
 * @property string|null $address
 * @property string|null $phone_number
 * @property string $profile_picture
 * @property int $point
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ComplaintSuggestion[] $complaint_suggestions
 * @property-read int|null $complaint_suggestions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserVoucher[] $vouchers
 * @property-read int|null $vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \App\Models\Contracts\UploadedFilesInterface {}
}

namespace App\Models{
/**
 * App\Models\UserVoucher
 *
 * @property int $id
 * @property int $voucher_id
 * @property int $user_id
 * @property int $used
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Voucher $voucher
 * @method static \Illuminate\Database\Eloquent\Builder|UserVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVoucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVoucher whereUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVoucher whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVoucher whereVoucherId($value)
 */
	class UserVoucher extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Voucher
 *
 * @property int $id
 * @property string $name
 * @property int $discount_value
 * @property int $point_need
 * @property int $active_status
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserVoucher[] $user_vouchers
 * @property-read int|null $user_vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereActiveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher wherePointNeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereUpdatedAt($value)
 */
	class Voucher extends \Eloquent {}
}

