<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Events\Dispatcher;

class MergeGuestCart
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        if (! $user) return;

        // Get guest cart content (global)
        $guestCart = \Cart::getContent();

        if (empty($guestCart) || $guestCart->isEmpty()) {
            return; // nothing to merge
        }

        // We'll attempt to merge all items; only clear guest cart if entire merge succeeds
        $sessionCart = \Cart::session($user->id);
        $mergedSuccessfully = true;

        try {
            foreach ($guestCart as $item) {
                $id = $item->id;
                $qty = isset($item->quantity) ? $item->quantity : (isset($item['quantity']) ? $item['quantity'] : 1);

                $existing = $sessionCart->get($id);
                if ($existing) {
                    $sessionCart->update($id, [
                        'quantity' => [
                            'relative' => true,
                            'value' => $qty,
                        ],
                    ]);
                } else {
                    $attributes = [];
                    if (isset($item->attributes)) {
                        $attributes = is_array($item->attributes) ? $item->attributes : (method_exists($item->attributes, 'toArray') ? $item->attributes->toArray() : (array) $item->attributes);
                    }

                    $sessionCart->add([
                        'id' => $id,
                        'name' => $item->name ?? ($item['name'] ?? ''),
                        'price' => $item->price ?? ($item['price'] ?? 0),
                        'quantity' => $qty,
                        'attributes' => $attributes,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            $mergedSuccessfully = false;
            \Log::error('MergeGuestCart failed during merge: ' . $e->getMessage());
        }

        // If everything merged without exceptions, clear the guest cart to avoid duplicates
        if ($mergedSuccessfully) {
            try {
                \Cart::clear();
            } catch (\Throwable $e) {
                // If clearing fails, log but do not throw
                \Log::error('Failed to clear guest cart after successful merge: ' . $e->getMessage());
            }
        }
    }
}
