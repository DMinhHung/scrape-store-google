<?php
/**
 * Smart Digitech LLC (c) 2020, All right reverse
 * GCI2020 Project
 */

namespace app\common\db\base;

use Yii;

/**
 * Extends only abstract class AddOnPackage generate by Giiant,
 * Please don't touch this class.
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $category
 * @property string $category_ids
 * @property string $additional_categories
 * @property string $cid
 * @property string $feature_id
 * @property string $address
 * @property string $borough
 * @property string $city
 * @property string $zip
 * @property string $region
 * @property string $country_code
 * @property string $place_id
 * @property string $phone
 * @property string $url
 * @property string $domain
 * @property string $logo
 * @property string $main_image
 * @property integer $total_photos
 * @property string $snippet
 * @property float $latitude
 * @property float $longitude
 * @property integer $is_claimed
 * @property string $attributes
 * @property string $place_topics
 * @property string $rating
 * @property string $rating_distribution
 * @property string $people_also_search
 * @property string $work_time
 * @property string $popular_times
 * @property string $local_business_links
 * @property string $contacts
 * @property string $check_url
 * @property string $price_level
 * @property float $hotel_rating
 *
 * @author dtsmart.vn
 * @since 1.0.0
 */
abstract class Business extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%business}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['latitude', 'longitude', 'hotel_rating'], 'number'],
            [['id', 'total_photos', 'is_claimed'], 'integer'],
            [['title', 'description', 'category', 'category_ids', 'additional_categories', 'cid', 'feature_id', 'address', 'borough', 'city', 'zip', 'region', 'country_code', 'place_id', 'phone', 'url', 'domain', 'logo', 'main_image', 'snippet', 'attributes', 'place_topics', 'rating', 'rating_distribution', 'people_also_search', 'work_time', 'popular_times', 'local_business_links', 'contacts', 'check_url', 'price_level'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
           'title' => 'Title',
            'description' => 'Description',
            'category' => 'Category',
            'category_id' => 'Category ID',
            'additional_categories' => 'Additional Categories',
            'hotel_rating' => 'Hotel Rating',
            'is_claimed' => 'Is Claimed',
            'address' => 'Address',
            'borough' => 'Borough',
            'city' => 'City',
            'zip' => 'Zip',
            'region' => 'Region',
            'country_code' => 'Country Code',
            'place_id' => 'Place ID',
            'phone' => 'Phone',
            'url' => 'Url',
            'domain' => 'Domain',
            'logo' => 'Logo',
            'main_image' => 'Main Image',
            'total_photos' => 'Total Photos',
            'snippet' => 'Snippet',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'attributes' => 'Attributes',
            'place_topics' => 'Place Topics',
            'rating' => 'Rating',
            'rating_distribution' => 'Rating Distribution',
            'people_also_search' => 'People also Search',
            'work_time' => 'Work Time',
            'popular_times' => 'Popular Times',
            'local_business_links' => 'Local Business Links',
            'contacts' => 'Contact',
            'check_url' => 'Check Url',
            'price_level' => 'Price Level',
        ];
    }



}
