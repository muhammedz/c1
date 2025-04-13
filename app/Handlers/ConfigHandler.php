namespace App\Handlers;

use Illuminate\Support\Facades\Log;
use UniSharp\LaravelFilemanager\Handlers\ConfigHandler as BaseConfigHandler;

class ConfigHandler extends BaseConfigHandler
{
    public function userField()
    {
        // Debug için log ekleyelim
        Log::channel('daily')->debug('File Manager Upload Debug - User Field:', [
            'user' => auth()->user(),
            'request' => request()->all(),
            'files' => request()->file(),
            'server' => request()->server(),
        ]);
        
        return parent::userField();
    }
} 