<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    protected $fillable = ['system_name', 'logo_path', 'favicon_path'];

    /**
     * Singleton: get the first (and only) settings row.
     */
    public static function get(): self
    {
        $setting = static::first();
        if (!$setting) {
            $setting = static::create([
                'system_name' => config('app.name'),
                'logo_path' => null,
                'favicon_path' => null,
            ]);
        }
        return $setting;
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path || !Storage::disk('public')->exists($this->logo_path)) {
            return null;
        }
        return asset('storage/' . $this->logo_path);
    }

    public function getFaviconUrlAttribute(): ?string
    {
        if (!$this->favicon_path || !Storage::disk('public')->exists($this->favicon_path)) {
            return null;
        }
        return asset('storage/' . $this->favicon_path);
    }
}
