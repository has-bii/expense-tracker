<?php

use App\Models\Notification;

class NotificationRepository
{
    public function getAll()
    {
        return Notification::all();
    }

    public function findById($id)
    {
        return Notification::findOrFail($id);
    }

    public function create(array $data)
    {
        return Notification::create($data);
    }

    public function update($id, array $data)
    {
        $category = Notification::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = Notification::findOrFail($id);
        return $category->delete();
    }
}
