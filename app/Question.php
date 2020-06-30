<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Question extends Model
{
    use VotableTrait;

    protected $table = 'questions';
    protected $fillable = ['title','body'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function favorites() {
        return $this->belongsToMany(User::class, 'favorites', 'question_id', 'user_id')->withTimestamps();
    }

    //Query
    // $question = Question::find(1);
    // $question->user->name;

    public function setTitleAttribute($value) {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
        // $this->attributes['slug'] = Str::slug($value);
    }

    // public function setBodyAttribute($value) {
    //     $this->attributes['body'] = clean($value);
    // }

    //accessor: thuc hien dinh dang thuoc tinh khi ta lay no ra tu instance cua model
    public function getUrlAttribute() {
        return route('questions.show', $this->slug);
    }

    public function getCreatedDateAttribute() {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute() {
        if($this->answers_count > 0) {
            if($this->best_answer_id) {
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    public function getBodyHtmlAttribute() {
        return clean($this->bodyHtml());
    }

    public function acceptBestAnswer(Answer $answer) {
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    public function isFavorited() {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }

    public function getIsFavoritedAttribute() {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute() {
        return $this->favorites->count();
    }

    public function getExcerptAttribute() {
        return $this->excerpt(250);
    }

    public function excerpt($length) {
        return str_limit(strip_tags($this->bodyHtml()), $length);
    }

    private function bodyHtml() {
        return \Parsedown::instance()->text($this->body);
    }
}
