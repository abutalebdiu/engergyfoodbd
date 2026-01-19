<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 rtl:text-right">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Subject
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Category
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Domain
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Body
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Limit
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Send Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Send SMS
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($mail_jobs as $mail_job)
                                    <tr class="bg-white border-b">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                           {{ $mail_job->title  }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $mail_job->subject  }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $mail_job->category ? $mail_job->category->title : ''  }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $mail_job->mail_domain->domain  }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $mail_job->body  }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $mail_job->per_hour_limit  }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($mail_job->status == 'done')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Done</span>
                                            @elseif($mail_job->status == 'continue')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Continue...</span>
                                            @else
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Draft</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <a
                                                href="{{  route('send-mail.send.email', ['id' => $mail_job->id])  }}"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                                                EMAIL
                                            </a>

                                        </td>
                                        <td>
                                            <a
                                            href="{{  route('send-mail.send.sms', ['id' => $mail_job->id])  }}"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                                            SMS
                                        </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
