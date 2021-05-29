@extends('backend.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Create New Invoice</h1>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-info">
            {{ session()->get('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    <form  action="{{route('itemSold.list')}}" method="post">
        @csrf

        {{-- @dd($customer); --}}

        <input type="hidden" name="total_amount" value="{{$totalPrice}}">
    <div class="row">
        <div class="col-md-6">
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Customer Name</label>
                <div class="col-sm-10">
                    <input type="text" name="customer_name" class="form-control" id="customer_name" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label fw-bolder">Email</label>
                <div class="col-sm-10">
                    <select class="form-select" name="customer_id" id="customer_id">
                        <option value="">select customer email</option>
                        @foreach ($customer as $data)
                            <option value="{{ $data->id }}">{{ $data->email }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Phone</label>
                <div class="col-sm-10">
                    <input type="text" name="phone" class="form-control" id="customer_phone" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Invoice No.</label>
                <div class="col-sm-10">
                    <input type="text" name="invoice_no" id="invoice_no" class="form-control" id="invoice_no" readonly >
                </div>
            </div>
        </div>
    </div>
    {{-- add product for sale --}}
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add Products
        </button>
    </div>

    <div class="col-md-12 mt-2">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">serial</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Quantity</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col" colspan="2">Sub Total</th>
                    <th scope="col" colspan="2">Handle</th>
                </tr>
            </thead>
            {{-- @dd($addItem) --}}
            @foreach ($addItem as $key=>$item)

            <tbody class="text-center">
                <tr>
                    <th scope="row">{{$key+1}}</th>
                    <td >{{$item->itemName->name}}</td>
                    <td>{{$item->item_quantity}}</td>
                    <td>{{$item->price}}</td>
                    <td colspan="2">{{$item->subtotal}} BDT</td>
                    <td colspan="2">
                        <a class="btn btn-danger" href={{ route('saleItem.delete', $item['id']) }}>Delete</a>
                    </td>
                </tr>
            </tbody>
            @endforeach

            <tfoot>
                <td colspan="2"></td>
                <td colspan="2" class="fw-bold">Total sold Product Quantity= {{$totalItems}}  </td>
                <td colspan="3" class="fw-bold"> Total Amount= {{$totalPrice}}</td>
            </tfoot>
        </table>
        <div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    </form>

    <div>


        <form method="post" action="{{ route('saleItem.create') }}">
            @csrf

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Select Name</label>
                                    <select class="form-select" name="item_id">
                                        <option selected>Select customer</option>
                                        @foreach ($customer as $data)
                                            <option value="{{$data->id}}" >{{ $data->email}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Select Name</label>
                                <select class="form-select" name="item_id">
                                    <option selected>Select Items</option>
                                    @foreach ($items as $data)
                                        <option value="{{$data->id}}" >{{ $data->name }}- {{$data->unit}}Qty</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Item Quantity</label>
                                <input type="number" name="item_quantity" class="form-control" id="exampleFormControlInput1"
                                    placeholder="500">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>

</div>

@endsection

@push('customer_js')
    <script>
        let customer_id = document.querySelector('#customer_id');
        let customer_name = document.querySelector('#customer_name');
        let customer_phone = document.querySelector('#customer_phone');
        let invoice_no = document.querySelector('#invoice_no');



        customer_id.addEventListener('change', (e) => {
            let id = e.target.value;

            const url = "{{ url('get-customer') }}/" + id;
            fetch(url)
                .then(res => res.json())
                .then(res => {
                    customer_name.value = res.data.f_name+' '+res.data.l_name;
                    customer_phone.value = res.data.phone;
                    invoice_no.value = res.invoice_no;
                })
        })




    </script>
@endpush