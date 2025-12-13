protected $fillable = [
    'user_id', 'jewellery_type', 'metal_type', 'budget', 
    'description', 'design_image_path', 'status', 
    'manager_comments', 'approved_at'
];

public function user() {
    return $this->belongsTo(User::class);
}