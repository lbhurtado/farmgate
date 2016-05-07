<?php

namespace App\Jobs;

use App\Repositories\ClusterRepository;
use App\Repositories\TokenRepository;
use App\Repositories\TownRepository;
use App\Criteria\Town2Criterion;
use App\Entities\ShortMessage;
use App\Entities\Cluster;

class ClaimToken extends Job
{
    private $message;

    private $contact;

    public function __construct(ShortMessage $short_message)
    {
        $this->message = $short_message->message;
        $this->contact = $short_message->contact;
    }

//    function sanitizeToken($input_line, &$matches)
//    {
//        $towns = \App::make(TownRepository::class)->skipPresenter();
//        $clusters = \App::make(ClusterRepository::class)->skipPresenter();
//
//        list($town, $number, $name) = $this->getTownNumberName($towns, $input_line);
//
//        if (isset($town) && isset($name) && isset($number)) {
//            if (preg_match("/(?:(?!0[a-z])(?<precinct>(?:(?:\d{1,3}\s*[a-z])|(?:[a-z]\s*\d{1,3})))|(?<cluster>\d{1,3}))/i", $number, $output_array)) {
//                extract($output_array);
//            }
//
//            $town = $towns->findByField('name', strtoupper($town))->first();
//
//
//            if (isset($cluster)) {
//                $cluster_object = $clusters->getByCriteria(new Town2Criterion($town))->where('name', $cluster)->first();
//
//            } elseif (isset($precinct)) {
//                $precinct = ltrim($precinct, "0");
//
//                $cluster_object = (new Cluster())->whereRaw("precincts REGEXP '[[:<:]]" . $precinct . "[[:>:]]'")
//                    ->with('town')->whereHas('town', function ($q) use ($town) {
//                        $q->where('id', $town->id);
//                    })->first();
//            }
//
//            $matches =
//                [
//                    'token' => $cluster_object->town->name . " " . $cluster_object->name,
//                    'handle' => $name
//                ];
//        }
//    }

    /**
     * @param TokenRepository $tokens
     */
    public function handle(TokenRepository $tokens)
    {
        $token = null;
        if (preg_match("/(?<token>.*\d)\s*(?<handle>.*)/i", $this->message, $matches))
        {
            //$this->sanitizeToken($this->message, $matches);
            $token = $tokens->findByField('code', $matches['token'])->first();
        }
        $tokenIsValid = !is_null($token);

        if ($tokenIsValid) $this->contact->claimToken($token->code, $matches['handle']);
    }

//    protected function getTownNumberName($towns, $input_line)
//    {
//        $town_regex = implode('|', $towns->all()->pluck('name')->toArray()); //get alias instead
//
//        if (preg_match("/\b(?<town>$town_regex)\b[^\d]*(?<number>(?:\d+\w?|\w?\d+)).+?(?=\w)(?<name>.*?[\w\s]*)/i", $input_line, $output_array))
//        {
//            return array_only($output_array, ['town', 'number', 'name']);
//        }
//        elseif (preg_match("/(?<number>(?:\d+\w?|\w?\d+)).+?(?=\w)\b(?<town>$town_regex)\b.+?(?=\w)(?<name>.*?[\w\s]*)/i", $input_line, $output_array))
//        {
//            return array_only($output_array, ['town', 'number', 'name']);
//        }
//
//        return array(null, null, null);
//    }
}
