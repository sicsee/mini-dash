import React, { createContext, useState, useContext, useEffect } from 'react'
import { supabase } from '@/supabase'

const AuthContext = createContext()

export const useAuth = () => {
  return useContext(AuthContext)
}

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null)
  const [username, setUsername] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  useEffect(() => {
    const fetchSession = async () => {
      const { data: session, error: sessionError } = await supabase.auth.getSession()
      if (sessionError) {
        console.error(sessionError)
        setError('Erro ao verificar a sessão')
      } else {
        if (session) {
          setUser(session.user)
          fetchUsername(session.user?.id)
        }
      }
      setLoading(false)
    }

    fetchSession()

    const { data: authListener } = supabase.auth.onAuthStateChange(
      async (event, session) => {
        setUser(session?.user)
        if (session?.user) fetchUsername(session.user.id)
      }
    )

    return () => {
      authListener?.unsubscribe()
    }
  }, [])

  const fetchUsername = async (user_id) => {
    if (!user_id) return
    const { data: usernameData, error } = await supabase
      .from('usernames')
      .select('username')
      .eq('user_id', user_id)
      .single()

    if (error) {
      console.error(error)
      setError('Erro ao obter nome de usuário')
      return
    }
    setUsername(usernameData?.username)
  }

  const login = async (email, password) => {
    const { user, session, error } = await supabase.auth.signInWithPassword({
      email,
      password,
    })

    if (error) {
      setError(error.message)
      throw error
    }

    setUser(user)
    fetchUsername(user.id)
  }

  const logout = async () => {
    await supabase.auth.signOut()
    setUser(null)
    setUsername(null)
  }

  // Exibição de carregamento enquanto a autenticação é verificada
  if (loading) {
    return <div>Carregando...</div>
  }

  return (
    <AuthContext.Provider
      value={{
        user,
        username,
        loading,
        login,
        logout,
        error,
      }}
    >
      {children}
    </AuthContext.Provider>
  )
}
