<?php

namespace App\Console\Commands;

use App\Enums\UpdateType;
use App\Indicators\IndicatorsService;

use App\Indicators\Spreadsheets\SpreadsheetDriveService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateIndicators extends Command
{

    private $driveService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indicators:update {update_type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update indicators';

    /**
     * Create a new command instance.
     *
     * @param SpreadsheetDriveService $driveService
     */
    public function __construct(SpreadsheetDriveService $driveService)
    {
        parent::__construct();
        $this->driveService = $driveService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \BenSampo\Enum\Exceptions\InvalidEnumMemberException
     */
    public function handle()
    {
        $arg = $this->argument('update_type');
        if (!UpdateType::hasKey($arg)) {
            $this->error('UpdateType not found!');
            return;
        }
        $update_type = UpdateType::getValue($arg);
        $update_type = UpdateType::getInstance($update_type);
        $data = Carbon::now();

        // Se for diario vai baixar a planilha pra fazer os calculos
        if ($update_type->is(UpdateType::Daily)) {
            if (!$this->driveService->downloadFromDrive()) {
                $this->error("Não consegui baixar a planilha!");
            }
        }
        \Log::debug("Atualizando o indicador: " . $update_type->key);

        /*
         *  Vai ser chamado só no proximo dia 00:10, seta a hora pra calcular no dia anterior
         *  Ver \App\Console\Kernel:schedule
         */
        if ($update_type->is(UpdateType::Daily) || $update_type->is(UpdateType::Monthly)) {
            $data = $data->subDay()->setHour(23)->setMinute(59);
        }

        resolve(IndicatorsService::class)->calculateAndSaveAll($update_type, $data, $this);
    }
}
