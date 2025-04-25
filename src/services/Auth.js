import { supabase } from '@/supabase'

export async function signUp(username, email, password) {
  const { data: authData, error: signUpError } = await supabase.auth.signUp({
    email,
    password
  })

  if (signUpError) throw signUpError

  const user = authData.user
  const user_id = user?.id

  // 2. Insere o username na tabela
  const { error: insertError } = await supabase
    .from('usernames')
    .insert([{ user_id, username }])

  if (insertError) throw insertError

  return authData
}

export const signIn = async (email, password) => {
  // 1. Login
  const { data: authData, error: signInError } = await supabase.auth.signInWithPassword({
    email,
    password
  })

  if (signInError) throw signInError

  const user = authData.user
  const user_id = user?.id

  // 2. Buscar o username
  const { data: usernameData, error: fetchError } = await supabase
    .from('usernames')
    .select('username')
    .eq('user_id', user_id)
    .single()

  if (fetchError) throw fetchError

  return {
    user,
    session: authData.session,
    username: usernameData.username
  }
}


export const signOut = async () => {
  const { error } = await supabase.auth.signOut()
  if (error) throw error
}
