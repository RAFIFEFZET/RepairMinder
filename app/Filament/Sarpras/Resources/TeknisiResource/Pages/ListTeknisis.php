<?php

namespace App\Filament\Sarpras\Resources\TeknisiResource\Pages;

use App\Filament\Sarpras\Resources\TeknisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeknisis extends ListRecords
{
    protected static string $resource = TeknisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
