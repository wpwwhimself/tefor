<?php

namespace App\Models;

use Wpwwhimself\Shipyard\Traits\HasStandardAttributes;
use Wpwwhimself\Shipyard\Traits\HasStandardFields;
use Wpwwhimself\Shipyard\Traits\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\View\ComponentAttributeBag;
use Mattiverse\Userstamps\Traits\Userstamps;

class StudentSession extends Model
{
    //

    public const META = [
        "label" => "Sesje",
        "icon" => "timer-sand",
        "description" => "Sesje korepetycji. Jedna sesja odzwierciedla jedno spotkanie z uczniem.",
        "role" => "teacher|technical",
        "ordering" => 12,
        "defaultSort" => "-date",
    ];

    use SoftDeletes, Userstamps;

    protected $fillable = [
        "student_id",
        "started_at",
        "duration_h",
        "cost",
    ];

    #region presentation
    public function __toString(): string
    {
        return $this->started_at->diffForHumans();
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->started_at->format("Y-m-d H:i"),
        );
    }

    public function displayTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("shipyard::components.app.h", [
                "lvl" => 3,
                "icon" => $this->icon ?? self::META["icon"],
                "attributes" => new ComponentAttributeBag([
                    "role" => "card-title",
                ]),
                "slot" => $this->started_at->format("Y-m-d H:i"),
            ])->render(),
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("shipyard::components.app.model.badges", [
                "badges" => $this->badges,
            ])->render()
        );
    }

    public function displayMiddlePart(): Attribute
    {
        return Attribute::make(
            get: fn () => view("shipyard::components.app.model.connections-preview", [
                "connections" => self::getConnections(),
                "model" => $this,
            ])->render()
            . view("shipyard::components.app.icon-label-value", [
                "icon" => "timer",
                "label" => "Czas trwania",
                "slot" => "$this->duration_h h",
            ])->render()
            . view("shipyard::components.app.icon-label-value", [
                "icon" => "cash",
                "label" => "Koszt",
                "slot" => "$this->cost zł",
            ])->render()
        );
    }
    #endregion

    #region fields
    use HasStandardFields;

    public const FIELDS = [
        "started_at" => [
            "type" => "datetime-local",
            "label" => "Data sesji",
            "icon" => "calendar",
            "required" => true,
        ],
        "duration_h" => [
            "type" => "number",
            "label" => "Czas trwania [h]",
            "icon" => "timer",
            "required" => true,
            "min" => 0,
            "step" => 0.25,
        ],
        "cost" => [
            "type" => "number",
            "label" => "Koszt [zł]",
            "icon" => "cash",
            "required" => true,
        ],
    ];

    public const CONNECTIONS = [
        "student" => [
            "model" => Student::class,
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
        "date" => [
            "label" => "Data",
            "compare-using" => "field",
            "discr" => "started_at",
        ],
    ];

    public const FILTERS = [
        "student" => [
            "label" => "Uczeń",
            "icon" => "account-school",
            "compare-using" => "field",
            "discr" => "student_id",
            "type" => "select",
            "operator" => "=",
            "selectData" => [
                "optionsFromScope" => [
                    Student::class,
                    "forConnection",
                ],
                "emptyOption" => "Wszyscy",
            ],
        ],
        "time" => [
            "label" => "Czas trwania [h]",
            "icon" => "timer",
            "compare-using" => "field",
            "discr" => "duration_h",
            "type" => "number",
            "operator" => "=",
        ],
        "cost" => [
            "label" => "Kwota",
            "icon" => "cash",
            "compare-using" => "field",
            "discr" => "cost",
            "type" => "number",
            "operator" => "=",
        ],
    ];

    #region scopes
    use HasStandardScopes;
    #endregion

    #region attributes
    protected function casts(): array
    {
        return [
            "started_at" => "datetime",
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
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    #endregion

    #region helpers
    #endregion
}
