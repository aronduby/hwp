<script>
    const PHOTOS_DOMAIN = `{{ config('urls.photos') }}`;
</script>
<script src="{{ mix( app('App\Services\MediaServices\MediaService')->getScript() ) }}"></script>