<?php

namespace App;

use App\Helpers\CodeHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Core\Helpers\SitemapHelper;
use Modules\Core\Models\SEO;
use Modules\Media\Helpers\FileHelper;

class BaseModel extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $slugField = '';
    protected $slugFromField = '';
    protected $cleanFields = [];
    protected $seo_type;
    public $translationForeignKey = 'origin_id';

    public static function getModelName()
    {
    }

    public static function getAsMenuItem($id)
    {
        return false;
    }

    public static function searchForMenu($q = false)
    {
        return [];
    }

    public function save(array $options = [])
    {
        // Clear Sitemap on Save
        if ($this->type or $this->sitemap_type) {
            $sitemapHelper = app()->make(SitemapHelper::class);
            $sitemapHelper->clear($this->sitemap_type ? $this->sitemap_type : $this->type);
        }

        if ($this->create_user) {
            $this->update_user = Auth::id();
        } else {
            $this->create_user = Auth::id();
        }
        if ($this->slugField && $this->slugFromField) {
            $slugField = $this->slugField;
            $this->$slugField = $this->generateSlug($this->$slugField);
        }
        $this->cleanFields();
        return parent::save($options); // TODO: Change the autogenerated stub
    }

    /**
     * @todo HTMLPurifier
     * @param array $fields
     */
    protected function cleanFields($fields = [])
    {
        if (empty($fields))
            $fields = $this->cleanFields;
        if (!empty($fields)) {
            foreach ($fields as $field) {

                if ($this->$field !== NULL) {
                    $this->$field = clean($this->$field, 'youtube');
                }
            }
        }
    }

    public function generateSlug($string = false, $count = 0)
    {
        $slugFromField = $this->slugFromField;
        if (empty($string))
            $string = $this->$slugFromField;
        $slug = $newSlug = $this->strToSlug($string);
        $newSlug = $slug . ($count ? '-' . $count : '');
        $model = static::select('count(id)');
        if ($this->id) {
            $model->where('id', '<>', $this->id);
        }
        $check = $model->where($this->slugField, $newSlug)->count();
        if (!empty($check)) {
            return $this->generateSlug($slug, $count + 1);
        }
        return $newSlug;
    }

    // Add Support for non-ascii string
    // Example বাংলাদেশ   ব্যাংকের    রিজার্ভের  অর্থ  চুরির   ঘটনায়   ফিলিপাইনের
    protected function strToSlug($string)
    {
        $slug = Str::slug($string);
        if (empty($slug)) {
            $slug = preg_replace('/\s+/u', '-', trim($string));
        }
        return $slug;
    }

    public function getDetailUrl()
    {
        return '';
    }

    public function getEditUrl()
    {
        return '';
    }

    public function getAuthor()
    {
        return $this->belongsTo("App\User", "create_user", "id")->withDefault();
    }

    public function author()
    {
        return $this->belongsTo("App\User", "create_user", "id")->withDefault();
    }

    public function vendor()
    {
        return $this->belongsTo("App\User", "vendor_id", 'id')->withDefault();
    }

    public function cacheKey()
    {
        return strtolower($this->table);
    }

    public function findById($id)
    {
        return Cache::rememberForever($this->cacheKey() . ':' . $id, function () use ($id) {
            return $this->find($id);
        });
    }

    public function currentUser()
    {
        return Auth::user();
    }

    public function origin()
    {
        return $this->hasOne(get_class($this), 'id', 'origin_id');
    }

    public function getIsTranslationAttribute()
    {
        if ($this->origin_id)
            return true;
        return false;
    }


    public function getTranslationsByLocalesAttribute()
    {
        $translations = $this->translations;
        $res = [];

        foreach ($translations as $translation) {
            $res[$translation->lang] = $translation;
        }
        return $res;
    }


    //    public static function findWithLang($id,$lang = '')
    //    {
    //        if(!$lang) $lang = request()->query('lang');
    //
    //        if(empty($lang) || is_default_lang($lang)) return parent::find($id);
    //
    //        $item = parent::where('origin_id',$id)->where('lang',$lang)->first();
    //
    //        if(empty($item)){
    //            $origin = parent::find($id);
    //
    //            $clone = $origin->replicate();
    //            $clone->lang = $lang;
    //            $clone->origin_id = $id;
    //            $clone->save();
    //
    //            return $clone;
    //        }
    //
    //        return $item;
    //    }
    //
    //    public static function findByWithLang($key,$value,$lang = '')
    //    {
    //        if(!$lang) $lang = request()->query('lang');
    //        if(!$lang) $lang = request()->route('lang');
    //
    //        if(empty($lang) || is_default_lang($lang)) return parent::where($key,$value)->first();
    //
    //        $item = parent::where($key,$value)->where('lang',$lang)->first();
    //
    //        return $item;
    //    }

    public function getIsPublishedAttribute()
    {

        if ($this->is_translation) {

            $origin = $this->origin;

            if (empty($origin))
                return false;
            return $origin->status == 'publish';
        } else {
            return $this->status == 'publish';
        }
    }

    public function saveSEO(\Illuminate\Http\Request $request, $locale = false)
    {
        if (!$this->seo_type)
            return;
        $seo_key = $this->seo_type;
        if (!empty($locale))
            $seo_key = $seo_key . "_" . $locale;
        $meta = SEO::where('object_id', $this->id)->where('object_model', $seo_key)->first();
        if (!$meta) {
            $meta = new SEO();
            $meta->object_id = $this->id;
            $meta->object_model = $seo_key;
        }
        $meta->fill($request->input());
        return $meta->save();
    }

    public function getSeoMeta($locale = false)
    {
        if (!$this->seo_type)
            return;
        $seo_key = $this->seo_type;
        if (!empty($locale))
            $seo_key = $seo_key . "_" . $locale;
        $meta = SEO::where('object_id', $this->id ? $this->id : $this->origin_id)->where('object_model', $seo_key)->first();
        if (!empty($meta)) {
            $meta = $meta->toArray();
        }
        $meta['slug'] = $this->slug;
        $meta['full_url'] = $this->getDetailUrl();
        $meta['service_title'] = $this->title ?? $this->name;
        $meta['service_desc'] = $this->short_desc;
        $meta['service_image'] = $this->image_id;
        return $meta;
    }

    public function getSeoMetaWithTranslation($locale, $translation)
    {
        if (is_default_lang($locale))
            return $this->getSeoMeta();
        if (!empty($translation->origin_id)) {
            $meta = $translation->getSeoMeta($locale);
            $meta['full_url'] = $this->getDetailUrl();
            $meta['slug'] = $this->slug;
            $meta['service_image'] = $this->image_id;
            ;
            return $meta;
        }
    }
    /**
     * @internal will change to private
     */
    public function getTranslationModelNameDefault(): string
    {
        $modelName = get_class($this);

        return $modelName . config('translatable.translation_suffix', 'Translation');
    }

    public function translations()
    {
        return $this->hasMany($this->getTranslationModelNameDefault(), 'origin_id');
    }
    public function translate($locale = false)
    {
        $translations = $this->translations;
        if (!empty($translations)) {
            foreach ($translations as $translation) {
                if ($translation->locale == $locale)
                    return $translation;
            }
        }
        return false;
    }
    public function getNewTranslation($locale)
    {

        $modelName = $this->getTranslationModelNameDefault();

        $translation = new $modelName();
        $translation->locale = $locale;
        $translation->origin_id = $this->id;

        return $translation;
    }

    public function translateOrOrigin($locale = false)
    {
        if (empty($locale) or is_default_lang($locale)) {
            $a = $this->getNewTranslation($locale);
            $a->fill($this->getAttributes());
        } else {
            $a = $this->translate($locale);
            if (!empty($a))
                return $a;
            $a = $this->getNewTranslation($locale);
            $a->fill($this->getAttributes());
        }
        if (!empty($this->casts)) {
            foreach ($this->casts as $key => $type) {
                if (!empty($a->casts) and !empty($a->casts[$key])) {
                    $a->setAttribute($key, $this->getAttribute($key));
                }
            }
        }
        return $a;
    }

    /**
     * @todo Save Translation or Origin Language
     * @param bool $locale
     * @param bool $saveSeo
     * @return bool|null
     */
    public function saveOriginOrTranslation($locale = false, $saveSeo = true)
    {
        if (!$locale or is_default_lang($locale) or empty(setting_item('site_enable_multi_lang'))) {
            $res = $this->save();
            if ($res && $saveSeo) {
                $this->saveSEO(request());
            }
            return $res;
        } elseif ($locale && $this->id) {
            $translation = $this->translateOrOrigin($locale);
            if ($translation) {
                $translation->fill(request()->input());
                $res = $translation->save();
                if ($res && $saveSeo) {
                    $translation->saveSEO(request(), $locale);
                }
                return $res;
            }
        }

        return false;
    }

    public function fillData($attributes)
    {
        parent::fill($attributes);
    }

    public function fillByAttr($attributes, $input)
    {
        if (!empty($attributes)) {
            foreach ($attributes as $item) {
                $this->$item = isset($input[$item]) ? ($input[$item]) : null;
            }
        }
    }

    public function fillByAttrNumber($attributes, $input, $decimals = 2)
    {
        if (!empty($attributes)) {
            foreach ($attributes as $item) {
                $this->$item = isset($input[$item]) ? ($input[$item]) : null;
                $this->$item = number_format($this->$item, $decimals);
                $this->$item = str_replace(',', '', $this->$item);
                if ($this->$item != null) {
                    //$this->$item = (string)$this->$item;
                }
            }
        }
        //dd($this->attributesToArray());
    }


    public function check_enable_review_after_booking()
    {
    }


    public static function getTableName()
    {
        return with(new static)->table;
    }

    public function hasPermissionDetailView()
    {
        if ($this->status == "publish") {
            return true;
        }
        if (Auth::id() and $this->create_user == Auth::id() and Auth::user()->hasPermissionTo('dashboard_vendor_access')) {
            return true;
        }
        return false;
    }

    public function getForSitemap()
    {
        $all = parent::query()->where('status', 'publish')->get();
        $res = [];
        foreach ($all as $item) {
            $res[] = [
                'loc' => $item->getDetailUrl(),
                'lastmod' => date('c', strtotime($item->updated_at ? $item->updated_at : $item->created_at)),
            ];
        }
        return $res;
    }

    public function getImageUrlAttribute($size = "medium")
    {
        $url = FileHelper::url($this->image_id, $size);
        return $url ? $url : '';
    }

    public static function buildFilterQuery(&$query, $filters)
    {
        $searchFilters = request()->input('search_query');
        $searchFilters = CodeHelper::cleanArray($searchFilters);
        $filters = CodeHelper::cleanArray($filters);
        if (is_array($searchFilters) && count($searchFilters) > 0) {
            if (is_array($filters) && count($filters) > 0) {
                foreach ($filters as $searchKey => $column) {
                    if (is_numeric($searchKey)) {
                        $searchKey = $column;
                    }
                    if (array_key_exists($searchKey, $searchFilters)) {
                        $searchString = trim($searchFilters[$searchKey]);
                        if ($searchString != null) {
                            if (is_string($column)) {
                                $query->where($column, 'LIKE', '%' . $searchString . '%');
                            } elseif (is_array($column)) {
                                $columns = $column;
                                $query->where(function ($q) use ($columns, $searchString) {
                                    foreach ($columns as $column) {
                                        $q->orWhere($column, 'LIKE', '%' . $searchString . '%');
                                    }
                                })->get();
                            }
                        }
                    }
                }
            }
        }
        $columns = request()->input('columns');
        $orders = request()->input('order');
        if (is_array($orders) && count($orders) > 0) {
            foreach ($orders as $order) {
                $columnKey = $order['column'];
                if (is_array($columns) && array_key_exists($columnKey, $columns)) {
                    $orderDir = $order['dir'];
                    $orderColumn = $columns[$columnKey]['name'];
                    $query->orderBy($orderColumn, $orderDir);
                }
            }
        }
    }

    public static function getActionButtons($links, $extraHtml = '')
    {
        $haveLink = false;
        $html = '<div class="table-actions"><ul>';

        if (is_array($links) && count($links) > 0) {
            foreach ($links as $action => $link) {

                $visible = true;
                if (array_key_exists('visible', $link)) {
                    $visible = $link['visible'];
                }

                if ($visible) {

                    $haveLink = true;
                    $extraAttributes = [];

                    if (array_key_exists('confirm', $link)) {
                        $confirm = $link['confirm'];
                        if ($confirm === true) {
                            $extraAttributes[] = 'onClick="return confirm(\'Are you sure?\');"';
                        }
                    }

                    if (array_key_exists('url', $link)) {
                        $url = $link['url'];
                        $icon = $text = $class = '';
                        if (array_key_exists('text', $link)) {
                            $text = $link['text'];
                        } else {
                            $text = ucwords($action);
                        }
                        if (array_key_exists('icon', $link)) {
                            $icon = $link['icon'];
                        } else {
                            switch ($action) {
                                case 'view':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="View">visibility</span>';
                                    $class = 'btn btn-view';
                                    break;
                                case 'edit':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Edit">edit</span>';
                                    $class = 'btn btn-edit';
                                    break;
                                case 'delete':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Delete">delete</span>';
                                    $class = 'btn btn-delete';
                                    break;
                                case 'remove':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Remove">close</span>';
                                    $class = 'btn btn-close';
                                    break;
                                case 'calendar':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="View">calendar_month</span>';
                                    $class = 'btn btn-calendar';
                                    break;
                                case 'clone':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Clone">content_copy</span>';
                                    $class = 'btn btn-clone';
                                    break;
                                case 'hide':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Hide">visibility_off</span>';
                                    $class = 'btn btn-hide';
                                    break;
                                case 'publish':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Publish">done_all</span>';
                                    $class = 'btn btn-publish';
                                    break;
                                case 'invoice':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Invoice">article</span>';
                                    $class = 'btn btn-invoice';
                                    break;
                                case 'share':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Share">share</span>';
                                    $class = 'btn btn-share';
                                    break;
                                case 'archive':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Archive">archive</span>';
                                    $class = 'btn btn-archive';
                                    break;
                                case 'checkin':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="CheckinSMS">sms</span>';
                                    $class = 'btn btn-checkin';
                                    break;
                                case 'checkout':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="CheckoutSMS">comment</span>';
                                    $class = 'btn btn-checkout';
                                    break;
                                case 'pending':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Pending">pending_actions</span>';
                                    $class = 'btn btn-checkout';
                                    break;
                                case 'approve':
                                    $icon = '<span class="material-icons" data-toggle="tooltip" data-placement="top" title="Approve">done</span>';
                                    $class = 'btn btn-checkout';
                                    break;
                            }
                        }

                        if (array_key_exists('extra', $link)) {
                            foreach ($link['extra'] as $attributeName => $attributeValue) {
                                $extraAttributes[] = $attributeName . '="' . $attributeValue . '"';
                            }
                        }

                        $is_form = false;
                        if (array_key_exists('is_form', $link)) {
                            $is_form = $link['is_form'];
                        }

                        $textLabel = false;
                        if (array_key_exists('text_label', $link)) {
                            $textLabel = $link['text_label'];
                        }

                        $target = '_self';
                        if (array_key_exists('target', $link)) {
                            $target = $link['target'];
                        }

                        if (array_key_exists('class', $link)) {
                            $class = $class . " " . $link['class'];
                        }

                        if ($textLabel) {
                            $html .= '<li><a class="' . $class . '" ' . implode(' ', $extraAttributes) . ' target="' . $target . '" href="' . $url . '" title="' . $text . '">' . $textLabel . '</a></li>';
                        } else {
                            if ($is_form) {
                                $html .= '<li ' . implode(' ', $extraAttributes) . '><form class="delete-modal-form" action="' . $url . '" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <button class="' . $class . '" type="submit" title="' . $text . '">' . $icon . '</button>
                            </form></li>';
                            } else {
                                $html .= '<li><a class="' . $class . '" ' . implode(' ', $extraAttributes) . ' target="' . $target . '" href="' . $url . '" title="' . $text . '">' . $icon . '</a></li>';
                            }
                        }
                    }
                }
            }
        }

        $html .= $extraHtml;
        $html .= '</ul></div>';

        if (!$haveLink && $extraHtml == null) {
            $html = '-';
        }
        return $html;
    }
}
