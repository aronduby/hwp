<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // make the settings model
        $this->schema->create('settings', function(Blueprint $table) {
            $table->increments('id');
            $table->morphs('has_settings');
            $table->json('settings');
            $table->timestamps();
        });

        // update the sites' settings
        $oldSettingsStorage = Storage::disk('settings-old');
        $sites = App\Models\Site::all();
        foreach ($sites as $site) {
            $fileName = $site->domain.'.json';
            if ($oldSettingsStorage->has($fileName)) {
                $oldSettings = json_decode($oldSettingsStorage->get($fileName));
                $site->settings()->create(['settings' => $oldSettings]);

                $oldSettingsStorage->delete($fileName);
            }
        }

        // copy over job instance settings
        $jobInstances = App\Models\JobInstance::all();
        foreach ($jobInstances as $instance) {
            $instance->settings()->create(['settings' => json_decode($instance->settings)]);
        }

        // then drop the settings column from the job instance table
        $this->schema->table('job_instances', function(Blueprint $table) {
            $table->dropColumn('settings');
        });
    }

    /**
     * Reverse the migrations.
     * NOTE! This won't work without a rollback of some code as well, specifically site settings from json files
     *
     * @return void
     */
    public function down()
    {
        // add the job instance settings column
        $this->schema->table('job_instances', function(Blueprint $table) {
            $table->json('settings')
                ->nullable()
                ->after('enabled');
        });

        // copy over the job instance settings
        $jobInstances = App\Models\JobInstance::all();
        foreach ($jobInstances as $instance) {
            $settingsModel = $instance->settings()->getResults();
            $instance->settings = json_encode($settingsModel->settings);
            $instance->save();
        }

        // recreate the site settings files
        $sites = App\Models\Site::all();
        foreach ($sites as $site) {
            $settingsStr = json_encode($site->settings()->getResults()->settings);
            $fileName = $site->domain . '.json';
            Storage::disk('settings-old')->put($fileName, $settingsStr);
        }

        // drop the settings table
        $this->schema->dropIfExists('settings');

    }
}
