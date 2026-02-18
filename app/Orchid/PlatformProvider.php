<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
        public function menu(): array
    {
        return [
            // Главная
            Menu::make('Get Started')
                ->icon('bs.book')
                ->title('Навигация')
                ->route(config('platform.index')),

            // Раздел с примерами (можно удалить или оставить)
            Menu::make('Примеры')
                ->icon('bs.collection')
                ->list([
                    Menu::make('Sample Screen')
                        ->icon('bs.collection')
                        ->route('platform.example')
                        ->badge(fn () => 6),

                    Menu::make('Form Elements')
                        ->icon('bs.card-list')
                        ->route('platform.example.fields')
                        ->active('*/examples/form/*'),

                    Menu::make('Layouts Overview')
                        ->icon('bs.window-sidebar')
                        ->route('platform.example.layouts'),

                    Menu::make('Grid System')
                        ->icon('bs.columns-gap')
                        ->route('platform.example.grid'),

                    Menu::make('Charts')
                        ->icon('bs.bar-chart')
                        ->route('platform.example.charts'),

                    Menu::make('Cards')
                        ->icon('bs.card-text')
                        ->route('platform.example.cards'),
                ]),

            // === ВАЖНО: ТВОЙ КАТАЛОГ ===
            Menu::make('Управление магазином')
                ->icon('bs.shop')
                ->title('Каталог товаров')
                ->list([
                    Menu::make('Категории')
                        ->icon('bs.folder')
                        ->route('platform.categories')
                        ->permission('platform.systems.users'),

                    Menu::make('Товары')
                        ->icon('bs.box')
                        ->route('platform.products')
                        ->permission('platform.systems.users'),
                ]),

            // Системные настройки
            Menu::make(__('Пользователи'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Управление доступом')),

            Menu::make(__('Роли'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            // Документация
            Menu::make('Документация')
                ->title('Справка')
                ->icon('bs.box-arrow-up-right')
                ->url('https://orchid.software/en/docs')
                ->target('_blank'),

            Menu::make('Changelog')
                ->icon('bs.box-arrow-up-right')
                ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                ->target('_blank')
                ->badge(fn () => Dashboard::version(), Color::DARK),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
