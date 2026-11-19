<?php
namespace App\Controllers;

use App\Libraries\TaxExplanationEngine;

class Simulation extends BaseController
{
    public function index()
    {
        return view('simulation/index');
    }

    public function calculate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $input = $this->request->getJSON(true);
        $engine = new TaxExplanationEngine();

        $etr = (float)($input['etr'] ?? 0);
        $rpt = (float)($input['rpt'] ?? 0);
        $der = (float)($input['der'] ?? 0);
        $growth = (float)($input['growth'] ?? 0);

        // Simulating the database row for the explanation engine
        $row = [
            'has_related_party' => (int)($input['has_related_party'] ?? 1),
            'has_rpt_transaction' => (int)($input['has_rpt_transaction'] ?? 1),
            'has_interest_debt' => (int)($input['has_interest_debt'] ?? 1)
        ];

        $explanations = [
            'etr'    => $engine->explainETR($etr),
            'rpt'    => $engine->explainRPT($rpt, $row),
            'der'    => $engine->explainDER($der),
            'growth' => $engine->explainSalesGrowth($growth, $row),
        ];

        return $this->response->setJSON([
            'success' => true,
            'explanations' => $explanations
        ]);
    }
}
