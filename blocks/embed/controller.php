<?php
namespace Concrete\Package\EmbedBlock\Block\Embed;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\SanitizeService;
use Embed\Embed;

class Controller extends BlockController
{
    protected $btTable = 'btEmbed';
    protected $btDefaultSet = 'multimedia';
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputLifetime = 86400; // check every day
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    /** @var string */
    protected $source;

    public function getBlockTypeName()
    {
        return t('Embed');
    }

    public function getBlockTypeDescription()
    {
        return t('Show information of URL.');
    }

    public function view()
    {
        try {
            $info = Embed::create($this->source);
        } catch (\Exception $e) {
            $info = null;
        }

        if (is_object($info)) {
            $this->set('info', $info);
        }
    }

    public function validate($args)
    {
        /** @var ErrorList $e */
        $e = $this->app->make('helper/validation/error');
        if (!isset($args['source']) || empty($args['source'])) {
            $e->add(t('You must input a URL of the source.'));
        }

        return $e;
    }

    public function save($data)
    {
        if ($data['source']) {
            $appUrl = Application::getApplicationURL();
            $pattern = '/' . preg_quote($appUrl, '/') . '\/' . DISPATCHER_FILENAME . '\?cID=([0-9]+)/i';
            $data['source'] = preg_replace_callback($pattern,
                static function ($matches) {
                    $c = Page::getByID($matches[1]);
                    if (is_object($c) && !$c->isError()) {
                        return $c->getCollectionLink();
                    }
                    return Page::getByID(Page::getHomePageID())->getCollectionLink();
                },
                $data['source']);
            /** @var SanitizeService $sanitizer */
            $sanitizer = $this->app->make('helper/security');
            $args['source'] = $sanitizer->sanitizeURL($data['source']);
        } else {
            $args['source'] = '';
        }

        parent::save($args);
    }

}