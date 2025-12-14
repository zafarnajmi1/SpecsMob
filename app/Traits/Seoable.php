<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Http\Request;

trait Seoable
{
    /**
     * Save SEO meta for the current model (polymorphic).
     *
     * @param  \Illuminate\Http\Request|array  $input
     * @return \App\Models\SeoMeta|null
     */
    public function saveSeo($input): ?SeoMeta
    {
        // Normalize to array of SEO fields
        if ($input instanceof Request) {
            $seoData = $input->only([
                'meta_title',
                'meta_description',
                'meta_keywords',
                'canonical_url',
                'og_title',
                'og_description',
                'og_image',
                'twitter_title',
                'twitter_description',
                'twitter_image',
                'json_ld',
            ]);
        } elseif (is_array($input)) {
            $seoData = array_intersect_key($input, array_flip([
                'meta_title',
                'meta_description',
                'meta_keywords',
                'canonical_url',
                'og_title',
                'og_description',
                'og_image',
                'twitter_title',
                'twitter_description',
                'twitter_image',
                'json_ld',
            ]));
        } else {
            $seoData = [];
        }

        // Remove empty values
        $seoData = array_filter($seoData, fn ($v) => $v !== null && $v !== '');

        // If everything is empty: optionally delete existing SEO
        if (empty($seoData)) {
            if ($this->seo()->exists()) {
                $this->seo()->delete();
            }
            return null;
        }

        // Upsert SEO for this model (morphOne)
        return $this->seo()->updateOrCreate([], $seoData);
    }

    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }
}
