<?php

namespace App\Console\Commands;

use Log;
use Parser;
use Exception;
use App\Category;
use ApaiIO\ApaiIO;
use ApaiIO\Operations\Search;
use Illuminate\Console\Command;
use ApaiIO\Operations\BrowseNodeLookup;
use ApaiIO\Configuration\GenericConfiguration;

class AmazonParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amazon:nodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse amazon nodes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $conf = new GenericConfiguration();
        $client = new \GuzzleHttp\Client();
        $request = new \ApaiIO\Request\GuzzleRequest($client);

        $conf
            ->setCountry('com')
            ->setAccessKey(env('AWS_API_KEY'))
            ->setSecretKey(env('AWS_API_SECRET_KEY'))
            ->setAssociateTag(env('AWS_ASSOCIATE_TAG'))
            ->setRequest($request);
        $this->apaiIO = new ApaiIO($conf);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $node_id = $this->ask('What is the node id you are going to fetch?');

        $this->parseNode($node_id);
    }

    public function parseNode($node_id)
    {
        $browseNodeLookup = new BrowseNodeLookup();
        $browseNodeLookup->setNodeId($node_id);

        try {
            $response = $this->apaiIO->runOperation($browseNodeLookup);

            $result = Parser::xml($response);

            $root = Category::firstOrCreate([
                'amazon_node_id' => $node_id,
                'name' => $result['BrowseNodes']['BrowseNode']['Name'],
            ]);

            if( !isset($result['BrowseNodes']['BrowseNode']['Children'])) {
                return;
            }

            foreach ($result['BrowseNodes']['BrowseNode']['Children']['BrowseNode'] as $key => $value) {
                if (Category::where('amazon_node_id', $value['BrowseNodeId'])->exists()) {
                    continue;
                }

                $root->children()->create([
                    'name' => $value['Name'],
                    'amazon_node_id' => $value['BrowseNodeId'],
                ]);
                sleep(1);
                $this->parseNode($value['BrowseNodeId']);
            }
        } catch (Exception $e) {
            Log::error($e.'. Node: '.$node_id);
        }

    }
}
