<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Models\Blog;
use App\Models\Testimonial;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make(
                'Supplements',
                Product::count()
            )
            ->description('Total products')
            ->color('warning'),

            Stat::make(
                'Services',
                Service::count()
            )
            ->description('Active services')
            ->color('success'),

            Stat::make(
                'Customers',
                User::count()
            )
            ->description('Registered users')
            ->color('primary'),

            Stat::make(
                'Blog Posts',
                Blog::count()
            )
            ->description('Published blogs')
            ->color('info'),

            Stat::make(
                'Testimonials',
                Testimonial::count()
            )
            ->description('Client reviews')
            ->color('success'),

            Stat::make(
                'Orders',
                Order::count()
            )
            ->description('Total orders')
            ->color('danger'),

        ];
    }
}