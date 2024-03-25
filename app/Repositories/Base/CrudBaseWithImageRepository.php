<?php
namespace App\Repositories\Base;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;

abstract class CrudBaseWithImageRepository {

    protected Model $model;
    protected array $relations = [];
    public array $filterable;

    public  string $image_name_request = 'image';

    public string  $image_name_model = 'image';
    public string  $image_path_location = "base";


    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->filterable = [

            "search" =>[
                'name'=>'string',
                'category_id'=>'number'
            ],
            "sort" => [
                'created_at' =>'desc'
            ],
            'custom'=> function($query){
                $query->select('id');
            },


        ];
    }



    public function create($data): Model
    {
        $image  =  ImageService::upload_image(new_image:$data[$this->image_name_request] ,upload_location:$this->image_path_location );
        return $this->model->create(array_merge($data ,[

            $this->image_name_model => $image
        ]));
    }

    public function edit(int $id,  $data): Model
    {
        $object =$this->findByID($id);
        $image  = $object[$this->image_name_model];
        if(isset($data[$this->image_name_request])){
            $image  = ImageService::update_image(new_image:$data[$this->image_name_request] , old_image_name:$image ,upload_location: $this->image_path_location);
        }



        $object->update(array_merge($data ,[

            $this->image_name_model => $image
        ]));

        return $object;
    }

    public function delete(int $id): bool
    {


        $object =   $this->findByID($id);

        ImageService::delete_image($object[$this->image_name_model]);

        return (bool) $object->delete();
    }

    public function getAll($is_pagination  , int $perPage = 8, $search=null )
    {
        $query =$this->applyFilters($search)->with($this->relations);

        if($is_pagination)$query = $query->paginate($perPage);
        else $query = $query->get();

        return  $query;
    }

    public function updateStatus(int $id, bool $newStatus , $status_column_name): bool
    {

        return (bool) $this->findByID($id)->fill([$status_column_name => $newStatus])->save();
    }

    public function findByID(int $id): Model | null
    {
        return $this->model->find($id);
    }

    protected function applyFilters(?string $filters)
    {
        $query = $this->model->newQuery();

            if (array_key_exists('search',$this->filterable)) {
                $this->applySearchCriteria($query, $filters);
            }

            if (array_key_exists('sort' , $this->filterable)) {
                $this->applySorting($query);
            }

            if (array_key_exists('custom' , $this->filterable)) {
                $this->applyCustom($query);
            }


        return $query;
    }

    protected function applySearchCriteria($query, ?string $search)
    {


        if ($search) {
            foreach($this->filterable['search'] as $key_name => $key_type){

                if($key_type == 'number'){

                    $query->where($key_name, $search);
                }elseif ($key_type == 'string'){
                    $query->where($key_name, 'LIKE', '%' . $search . '%');

                }
            }

        }
    }

    protected function applySorting($query)
    {
        foreach($this->filterable['sort'] as $key => $value){


                $query->orderBy($key, $value);

        }
    }
    protected function applyCustom($query)
    {

            if (is_callable($this->filterable['custom'])) {

                $this->filterable['custom']($query);
            }

    }
}
