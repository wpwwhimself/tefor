<?php

namespace App\Models;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    ];

    use SoftDeletes, Userstamps;

    protected $fillable = [
        "student_id",
        "started_at",
        "duration_h",
        "cost",
    ];

    public function __toString(): string
    {
        return $this->started_at->diffForHumans();
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->started_at->format("d.m.Y, H:i"),
        );
    }

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
