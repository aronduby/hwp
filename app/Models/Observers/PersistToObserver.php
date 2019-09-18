<?php


namespace App\Models\Observers;

use App\Models\Contracts\IPersistTo;

/**
 * Class PersistElsewhereObserver
 *
 *
 * @package App\Models\Observers
 */
class PersistToObserver
{
    protected function useReadTable(IPersistTo $model)
    {
        $model->setTable($model->getReadTable());
    }

    protected function useWriteTable(IPersistTo $model)
    {
        $model->setTable($model->getWriteTable());
    }

    /**
     * Switch the model to use the write table before it goes to the DB
     * @param IPersistTo $model
     */
    public function creating(IPersistTo $model)
    {
        $this->useWriteTable($model);
    }

    /**
     * Switch the model to use the write table before it goes to the DB
     * @param IPersistTo $model
     */
    public function updating(IPersistTo $model)
    {
        $this->useWriteTable($model);
    }

    /**
     * Switch the model to use the write table before it goes to the DB
     * @param IPersistTo $model
     */
    public function saving(IPersistTo $model)
    {
        $this->useWriteTable($model);
    }

    /**
     * Switch the model to use the write table before it goes to the DB
     * @param IPersistTo $model
     */
    public function deleting(IPersistTo $model)
    {
        $this->useWriteTable($model);
    }

    /**
     * Switch the model to use the write table before it goes to the DB
     * @param IPersistTo $model
     */
    public function restoring(IPersistTo $model)
    {
        $this->useWriteTable($model);
    }


    /**
     * Model has been written to the BD, switch back to the read table
     * @param IPersistTo $model
     */
    public function created(IPersistTo $model)
    {
        $this->useReadTable($model);
    }

    /**
     * Model has been written to the BD, switch back to the read table
     * @param IPersistTo $model
     */
    public function updated(IPersistTo $model)
    {
        $this->useReadTable($model);
    }

    /**
     * Model has been written to the BD, switch back to the read table
     * @param IPersistTo $model
     */
    public function saved(IPersistTo $model)
    {
        $this->useReadTable($model);
    }

    /**
     * Model has been written to the BD, switch back to the read table
     * @param IPersistTo $model
     */
    public function deleted(IPersistTo $model)
    {
        $this->useReadTable($model);
    }

    /**
     * Model has been written to the BD, switch back to the read table
     * @param IPersistTo $model
     */
    public function restored(IPersistTo $model)
    {
        $this->useReadTable($model);
    }

}