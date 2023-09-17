@inject('activeSeason', 'App\Models\ActiveSeason')
@inject('mediaService', 'App\Services\MediaServices\MediaService')
<script>
    const CLOUDINARY_URL = `https://res.cloudinary.com`;
    const CLOUDINARY_CLOUD_NAME = `{{ $activeSeason->settings->get('cloudinary.cloud_name') }}`;
</script>
<script src="{{ mix('js/gallery/cloudinary.js') }}"></script>