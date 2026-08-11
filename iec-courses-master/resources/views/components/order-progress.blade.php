@props(['order'])

@php
    $orderModel = is_a($order, \App\Models\Order::class) ? $order : (isset($order['rawOrder']) ? $order['rawOrder'] : null);
    $rawStatus = strtolower($orderModel ? ($orderModel->status ?: 'pending') : ($order['status'] ?? 'pending'));
    
    // Canonical Status Mapping matching Admin Order System
    $statusMap = [
        'pending'   => ['label' => 'Pending', 'step' => 1],
        'paid'      => ['label' => 'Processing', 'step' => 2],
        'processing'=> ['label' => 'Processing', 'step' => 2],
        'shipped'   => ['label' => 'Shipped', 'step' => 3],
        'completed' => ['label' => 'Delivered', 'step' => 4],
        'delivered' => ['label' => 'Delivered', 'step' => 4],
        'rejected'  => ['label' => 'Cancelled', 'step' => 0],
        'cancelled' => ['label' => 'Cancelled', 'step' => 0],
        'failed'    => ['label' => 'Cancelled', 'step' => 0],
    ];

    $statusInfo = $statusMap[$rawStatus] ?? ['label' => 'Processing', 'step' => 2];
    $stepStatus = $statusInfo['step'];
    $isCancelled = ($stepStatus === 0);

    // Real database timestamps (using app timezone)
    $createdAt = $orderModel ? $orderModel->created_at : (isset($order['createdAt']) ? \Carbon\Carbon::parse($order['createdAt']) : (isset($order['orderDate']) ? \Carbon\Carbon::parse($order['orderDate']) : null));
    $formattedCreatedAt = $createdAt ? $createdAt->format('M j, Y, g:i A') : 'Pending';

    $updatedAt = $orderModel ? $orderModel->updated_at : (isset($order['updatedAt']) ? \Carbon\Carbon::parse($order['updatedAt']) : null);
    $formattedUpdatedAt = ($updatedAt && $updatedAt->ne($createdAt)) ? $updatedAt->format('M j, Y, g:i A') : null;
@endphp

<div class="gt-confirm-card gt-order-progress-card" style="background: #FFFFFF; border: 1px solid #EFEAE3; border-radius: 16px; padding: 24px 28px; box-shadow: 0 4px 16px rgba(92, 62, 33, 0.03);">
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <i data-lucide="clock" style="width: 22px; height: 22px; color: #5C3E21;"></i>
        <h3 style="font-size: 1.2rem; font-weight: 700; color: #3A2518; margin: 0;">Order Progress</h3>
    </div>
    <p style="font-size: 0.85rem; color: #7A6E65; margin: 0 0 24px 0;">We’ll keep you updated every step of the way.</p>

    @if($isCancelled)
        <!-- Terminal Cancelled Progress Stepper -->
        <div class="gt-progress-stepper-wrapper" style="position: relative; padding: 0 10px;">
            <div class="gt-stepper-line" style="position: absolute; top: 22px; left: 40px; right: 40px; height: 3px; background: #fee2e2; z-index: 1;">
                <div class="gt-stepper-line-active" style="height: 100%; background: #ef4444; width: 100%;"></div>
            </div>

            <div class="gt-stepper-nodes" style="display: flex; justify-content: space-between; position: relative; z-index: 2;">
                <!-- Step 1: Order Confirmed -->
                <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div class="gt-step-icon completed" style="width: 44px; height: 44px; border-radius: 50%; background: #5C3E21; border: 2px solid #5C3E21; color: #FFFFFF; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                        <i data-lucide="check-circle-2" style="width: 20px; height: 20px;"></i>
                    </div>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #3A2518;">Order Confirmed</span>
                    <span style="font-size: 0.72rem; color: #8A7E74; margin-top: 2px;">{{ $formattedCreatedAt }}</span>
                </div>

                <!-- Step 2: Cancelled -->
                <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div class="gt-step-icon cancelled" style="width: 44px; height: 44px; border-radius: 50%; background: #ef4444; border: 2px solid #ef4444; color: #FFFFFF; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                        <i data-lucide="x-circle" style="width: 20px; height: 20px;"></i>
                    </div>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #ef4444;">Cancelled</span>
                    <span style="font-size: 0.72rem; color: #ef4444; margin-top: 2px;">{{ $formattedUpdatedAt ?? 'Order Cancelled' }}</span>
                </div>
            </div>
        </div>
    @else
        <!-- Canonical 4-Step Progress Stepper -->
        <div class="gt-progress-stepper-wrapper" style="position: relative; padding: 0 10px;">
            <div class="gt-stepper-line" style="position: absolute; top: 22px; left: 40px; right: 40px; height: 3px; background: #EFE8DF; z-index: 1;">
                <div class="gt-stepper-line-active" style="height: 100%; background: #5C3E21; width: {{ max(0, min(100, ($stepStatus - 1) * 33.333)) }}%;"></div>
            </div>

            <div class="gt-stepper-nodes" style="display: flex; justify-content: space-between; position: relative; z-index: 2;">
                
                <!-- Step 1: Order Confirmed -->
                <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div class="gt-step-icon {{ $stepStatus >= 1 ? 'completed' : '' }}" style="width: 44px; height: 44px; border-radius: 50%; background: {{ $stepStatus >= 1 ? '#5C3E21' : '#FFFFFF' }}; border: 2px solid {{ $stepStatus >= 1 ? '#5C3E21' : '#DCD2C5' }}; color: {{ $stepStatus >= 1 ? '#FFFFFF' : '#A09386' }}; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                        <i data-lucide="check-circle-2" style="width: 20px; height: 20px;"></i>
                    </div>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #3A2518;">Order Confirmed</span>
                    <span style="font-size: 0.72rem; color: #8A7E74; margin-top: 2px;">{{ $formattedCreatedAt }}</span>
                </div>

                <!-- Step 2: Processing -->
                <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div class="gt-step-icon {{ $stepStatus >= 2 ? 'completed' : '' }}" style="width: 44px; height: 44px; border-radius: 50%; background: {{ $stepStatus >= 2 ? '#5C3E21' : '#FFFFFF' }}; border: 2px solid {{ $stepStatus >= 2 ? '#5C3E21' : '#DCD2C5' }}; color: {{ $stepStatus >= 2 ? '#FFFFFF' : '#A09386' }}; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                        <i data-lucide="package" style="width: 20px; height: 20px;"></i>
                    </div>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #3A2518;">Processing</span>
                    <span style="font-size: 0.72rem; color: #8A7E74; margin-top: 2px;">{{ $stepStatus >= 2 ? ($rawStatus === 'paid' && $formattedUpdatedAt ? $formattedUpdatedAt : 'Processing') : 'Pending' }}</span>
                </div>

                <!-- Step 3: Shipped -->
                <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div class="gt-step-icon {{ $stepStatus >= 3 ? 'completed' : '' }}" style="width: 44px; height: 44px; border-radius: 50%; background: {{ $stepStatus >= 3 ? '#5C3E21' : '#FFFFFF' }}; border: 2px solid {{ $stepStatus >= 3 ? '#5C3E21' : '#DCD2C5' }}; color: {{ $stepStatus >= 3 ? '#FFFFFF' : '#A09386' }}; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                        <i data-lucide="truck" style="width: 20px; height: 20px;"></i>
                    </div>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #3A2518;">Shipped</span>
                    <span style="font-size: 0.72rem; color: #8A7E74; margin-top: 2px;">{{ $stepStatus >= 3 ? ($rawStatus === 'shipped' && $formattedUpdatedAt ? $formattedUpdatedAt : 'Shipped') : 'Pending' }}</span>
                </div>

                <!-- Step 4: Delivered -->
                <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div class="gt-step-icon {{ $stepStatus >= 4 ? 'completed' : '' }}" style="width: 44px; height: 44px; border-radius: 50%; background: {{ $stepStatus >= 4 ? '#5C3E21' : '#FFFFFF' }}; border: 2px solid {{ $stepStatus >= 4 ? '#5C3E21' : '#DCD2C5' }}; color: {{ $stepStatus >= 4 ? '#FFFFFF' : '#A09386' }}; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                        <i data-lucide="home" style="width: 20px; height: 20px;"></i>
                    </div>
                    <span style="font-size: 0.85rem; font-weight: 700; color: #3A2518;">Delivered</span>
                    <span style="font-size: 0.72rem; color: #8A7E74; margin-top: 2px;">{{ $stepStatus >= 4 ? ($rawStatus === 'completed' && $formattedUpdatedAt ? $formattedUpdatedAt : 'Delivered') : 'Pending' }}</span>
                </div>

            </div>
        </div>
    @endif
</div>
