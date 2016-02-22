<?php namespace Harmony\Support;

class Repository
{
	protected $model;

	public function __call($method, $args)
	{
		return call_user_func_array(array($this->getModelInstance(), $method), $args);
	}

	public function create($modelOrData = [], $skipValidation = false)
	{
		$modelClass = $this->getModel();

		$model = $modelOrData instanceof $modelClass ? $modelOrData : $this->getModelInstance($modelOrData);

		$validator = $this->getModelValidator();
		if (! $skipValidation && $validator && $errors = $validator::make($modelOrData)->errors()) {
			throw $this->getModelValidatorExceptionInstance($errors);
		}

		return $model;
	}

	public function destroy($modelOrId)
	{
		$modelClass = $this->getModel();

		if ($modelOrId instanceof $modelClass) {
			$model = $modelOrId;
		} else {
			$model = $this->getModel();
			$model = $model::find($modelOrId);
		}

		return $model->delete();
	}

	public function edit($modelOrId, $data = array(), $skipValidation = false)
	{
		$modelClass = $this->getModel();

		if ($modelOrId instanceof $modelClass) {
			$model = $modelOrId;
		} else {
			$model = $this->getModel();
			$model = $model::find($modelOrId);
		}

		$model->fill($data);

		$validator = $this->getModelValidator();
		if (! $skipValidation && $validator && count($data) && $errors = $validator::make(array_merge($data, [ 'id' => $model->id ]), 'edit')->errors()) {
			throw $this->getModelValidatorExceptionInstance($errors);
		}

		return $model;
	}

	public function store($modelOrData)
	{
		$modelClass = $this->getModel();

		$model = $modelOrData instanceof $modelClass ? $modelOrData : $this->create($modelOrData);

		$model->save();

		return $model;
	}

	public function update($modelOrId, $data = [])
	{
		if (is_array($modelOrId)) {
			return $this->query()->update($modelOrId);
		}

		$model = $this->edit($modelOrId, $data);

		$model->save();

		return $model;
	}

	protected function getModel()
	{
		if ($this->model) {
			return $this->model;
		}

		if (class_exists($class = preg_replace('/sRepository$/', '', get_called_class()))) {
			return $class;
		} else {
			throw new \Exception('No model specified for "' . get_called_class() . '".');
		}
	}

	protected function getModelInstance($data = array())
	{
		$model = $this->getModel();

		return new $model($data);
	}

	protected function getModelValidator()
	{
		$validator = '\\' . $this->getModel() . 'Validator';

		return class_exists($validator) ? $validator : null;
	}

	protected function getModelValidatorInstance($data)
	{
		$validator = $this->getModelValidator();

		return new $validator($data);
	}

	protected function getModelValidatorException()
	{
		$tokens = explode('\\', $this->getModel());

		array_splice($tokens, 2, 0, [ 'Exceptions' ]);

		return '\\' . implode('\\', $tokens) . 'ValidationException';
	}

	protected function getModelValidatorExceptionInstance($data)
	{
		$exception = $this->getModelValidatorException();

		return new $exception($data);
	}
}
