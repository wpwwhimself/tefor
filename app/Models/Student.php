<?php

namespace App\Models;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\View\ComponentAttributeBag;
use Mattiverse\Userstamps\Traits\Userstamps;

class Student extends Model
{
    //

    public const META = [
        "label" => "Uczniowie",
        "icon" => "account-school",
        "description" => "Uczniowie zapisani na zajęcia.",
        "role" => "teacher",
        "ordering" => 11,
    ];

    use SoftDeletes, Userstamps;

    protected $fillable = [
        "name",
        "nickname",
        "student_status_id",
        "contact_info",
        "description",
        "default_rate",
        "default_rate_below_hour",
    ];

    #region presentation
    public function __toString(): string
    {
        return $this->name;
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name,
        );
    }

    public function displayTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.h", [
                "lvl" => 3,
                "icon" => $this->icon ?? self::META["icon"],
                "attributes" => new ComponentAttributeBag([
                    "role" => "card-title",
                ]),
                "slot" => $this->name,
            ])->render(),
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status,
        );
    }

    public function displayMiddlePart(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.icon-label-value", [
                "icon" => "cash",
                "label" => "Stawka (za godzinę/za mniej niż godzinę)",
                "slot" => "$this->default_rate / $this->default_rate_below_hour zł",
            ])->render(),
        );
    }
    #endregion

    #region fields
    use HasStandardFields;

    public const FIELDS = [
        "nickname" => [
            "type" => "text",
            "label" => "Pseudonim",
            "hint" => "Wykorzystywany w kalendarzu",
            "icon" => "text-short",
        ],
        "contact_info" => [
            "type" => "JSON",
            "columnTypes" => [ // for JSON
                "Pole" => "text",
                "Wartość" => "text",
            ],
            "label" => "Dane kontaktowe",
            "hint" => "Numer telefonu, adres email, ...",
            "icon" => "phone",
        ],
        "description" => [
            "type" => "TEXT",
            "label" => "Opis",
            "icon" => "text",
        ],
        "default_rate" => [
            "type" => "number",
            "label" => "Domyślna stawka",
            "icon" => "cash",
            "min" => 0,
            "step" => 0.01,
        ],
        "default_rate_below_hour" => [
            "type" => "number",
            "label" => "Domyślna stawka (poniżej godziny)",
            "icon" => "cash",
            "hint" => "Używana, gdy sesja trwa mniej niż godzinę",
            "min" => 0,
            "step" => 0.01,
        ],
    ];

    public const CONNECTIONS = [
        "student_status" => [
            "model" => StudentStatus::class,
            "mode" => "one",
            // "field_name" => "",
            // "field_label" => "",
        ],
    ];

    public const ACTIONS = [
        // [
        //     "icon" => "",
        //     "label" => "",
        //     "show-on" => "<list|edit>",
        //     "route" => "",
        //     "role" => "",
        //     "dangerous" => true,
        // ],
    ];
    #endregion

    // use CanBeSorted;
    public const SORTS = [
        // "<name>" => [
        //     "label" => "",
        //     "compare-using" => "function|field",
        //     "discr" => "<function_name|field_name>",
        // ],
    ];

    public const FILTERS = [
        // "<name>" => [
        //     "label" => "",
        //     "icon" => "",
        //     "compare-using" => "function|field",
        //     "discr" => "<function_name|field_name>",
        //     "mode" => "<one|many>",
        //     "operator" => "",
        //     "options" => [
        //         "<label>" => <value>,
        //     ],
        // ],
    ];

    #region scopes
    use HasStandardScopes;

    public function scopeForPicking($query)
    {
        $query->visible()
            ->get()
            ->map(fn ($s) => [
                "label" => $s,
                "value" => $s->id,
            ])
            ->toArray();
    }
    #endregion

    #region attributes
    protected function casts(): array
    {
        return [
            "contact_info" => "collection",
        ];
    }

    use HasStandardAttributes;

    // public function badges(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => [
    //             [
    //                 "label" => "",
    //                 "icon" => "",
    //                 "class" => "",
    //                 "condition" => "",
    //             ],
    //         ],
    //     );
    // }
    #endregion

    #region relations
    public function status()
    {
        return $this->belongsTo(StudentStatus::class, "student_status_id");
    }

    public function sessions()
    {
        return $this->hasMany(StudentSession::class);
    }
    #endregion

    #region helpers
    public function calculateCost(float $duration_h): float
    {
        return round($duration_h * ($duration_h < 1
            ? $this->default_rate_below_hour
            : $this->default_rate
        ), 2);
    }
    #endregion
}
